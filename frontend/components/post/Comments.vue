<template>
  <div>
    <div
      :class="post.type !== 'playlist' ? 'highlight-text' : ''"
      w:cursor="pointer"
      w:text="sm"
    >
      <ArrowIcon w:pos="relative" w:w="3" w:m="r-0.5" w:display="inline-block" :w:transform="showComments || showCommentForm ? 'gpu rotate-180' : ''" />
      <span v-if="post.comments && post.commentCount > 0" @click.prevent="!showComments ? fetchComments() : showComments = showCommentForm = false"> {{ !showComments ? $t('post.showComments', { count: post.commentCount }) :  $t('post.hideComments', { count: post.commentCount }) }}</span>
      <span v-else @click.prevent="showCommentForm = !showCommentForm">{{ $t('post.postComment') }}</span>
    </div>

    <div v-if="comments && showComments || showCommentForm" w:pos="relative">
      <div v-if="comments && showComments">
        <div v-for="(comment, key) in comments.value" :key="key">
          <PostCommentItem :comment="comment" />
        </div>
      </div>
      <form v-if="showCommentForm" w:pos="sticky lg:static" w:bottom="0" w:left="0" w:right="0" w:p="t-4 b-4 x-4 lg:(x-0 b-0)" w:m="-b-10 -r-10 -l-10 lg:(b-0 r-0 l-0)" @submit.prevent="submitComment">
        <div w:container="~ lg:none" w:pos="relative">
          <textarea v-model="commentBody" v-autogrow class="input-text" w:p="x-4 y-2 r-8" rows="1" w:text="white md" w:resize="none" :placeholder="$t('post.commentPlaceholder')" @keyup.enter="submitComment" @click.prevent="showLoginIfNotLoggedIn"></textarea>
          <button type="submit" w:pos="absolute" w:w="3" w:right="3" w:top="2.5" w:maxh="3xl" w:transform="gpu rotate-180" w:text="white/50" w:z="100"><ArrowIcon /></button>
        </div>
      </form>
    </div>
    <div v-else-if="post.comments && !showComments">
      <div v-for="(comment, key) in post.comments" :key="key">
        <PostCommentItem :comment="comment" />
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useAsync, ref, useContext, useStore } from '@nuxtjs/composition-api'
import { TextareaAutogrowDirective } from 'vue-textarea-autogrow-directive'
import { postApi } from '@/api/post'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'


export default defineComponent({
  components: {
    ArrowIcon
  },
  directives: {
    'autogrow': TextareaAutogrowDirective
  },
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  emits: [
    'commentsLoaded'
  ],
  setup(props, context) {
    const comments = ref([])
    const showComments = ref(false)
    const showCommentForm = ref(false)
    const commentBody = ref('')
    const { $auth } = useContext()
    const store = useStore()

    const { fetchPostsByPost, submitCommentByPost } = postApi()

    function fetchComments() {
      const newComments = useAsync(() => fetchPostsByPost(props.post.id), `comments_${props.post.id}`)

      if (comments.value.length > 0) {
        comments.value.push(newComments.splice(comments.value.length))
      } else {
        comments.value = newComments
      }

      showCommentForm.value = true
      showComments.value = true
    }

    function submitComment() {
      if ($auth.loggedIn) {
        submitCommentByPost(props.post.id, commentBody.value, props.post.campaign.id).then(function() {
          commentBody.value = ''
          fetchComments()
          context.emit('commentsLoaded', props.post.id)
        })
      }
    }

    function showLoginIfNotLoggedIn() {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      }
    }

    return {
      comments,
      showComments,
      showCommentForm,
      commentBody,
      store,
      fetchComments,
      submitComment,
      showLoginIfNotLoggedIn
    }
  }
})
</script>
