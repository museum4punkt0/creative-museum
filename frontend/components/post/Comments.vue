<template>
  <div>
    <div
      class="highlight-text"
      w:cursor="pointer"
      w:text="sm"
    >
      <ArrowIcon w:pos="relative" w:m="r-0.5" w:display="inline-block" :w:transform="showComments || showCommentForm ? 'gpu rotate-180' : ''" />
      <span v-if="post.comments && post.commentCount > 0" @click.prevent="!showComments ? fetchComments() : showComments = showCommentForm = false"> {{ !showComments ? $t('post.showComments', { count: post.commentCount }) :  $t('post.hideComments', { count: post.commentCount }) }}</span>
      <span v-else @click.prevent="showCommentForm = !showCommentForm">{{ $t('post.postComment') }}</span>
    </div>

    <div v-if="comments && showComments">
      <div v-for="(comment, key) in comments.value" :key="key">
        <PostCommentItem :comment="comment" />
      </div>
    </div>

    <form v-if="showCommentForm" w:m="t-4" @submit.prevent="submitComment">
      <textarea  v-model="commentBody" class="input-text" w:p="x-4 y-2" w:text="xs">Kommentar verfassen</textarea>
      <input type="submit" class="btn-primary" value="submit" />
    </form>
  </div>
</template>
<script>
import { defineComponent, useAsync, ref, toRefs } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'

export default defineComponent({
  components: {
    ArrowIcon
  },
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  setup(props, context) {
    const comments = ref([])
    const showComments = ref(false)
    const showCommentForm = ref(false)
    const commentBody = ref('')

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
      submitCommentByPost(props.post.id, commentBody.value, props.post.campaign.id).then(function() {
        fetchComments()
        context.emit('commentsLoaded', props.post.id)
      })
    }

    return {
      comments,
      showComments,
      showCommentForm,
      commentBody,
      fetchComments,
      submitComment
    }
  }
})
</script>
