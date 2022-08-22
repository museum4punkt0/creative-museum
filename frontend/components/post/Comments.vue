<template>
  <div>
    <div
      class="cursor-pointer text-sm"
      :class="post.type !== 'playlist' ? 'highlight-text' : ''"
    >
      <ArrowIcon
        class="relative w-3 mr-0.5 inline-block transform-gpu"
        w:pos="relative"
        w:w="3"
        w:m="r-0.5"
        w:display="inline-block"
        :class="showComments || showCommentForm ? 'transform-gpu rotate-180' : ''"
      />
      <span
        v-if="post.comments && post.commentCount > 0"
        @click.prevent="
          !showComments
            ? fetchComments()
            : (showComments = showCommentForm = false)
        "
      >
        {{
          !showComments
            ? $t('post.showComments', { count: post.commentCount })
            : $t('post.hideComments', { count: post.commentCount })
        }}</span
      >
      <span v-else @click.prevent="showCommentForm = !showCommentForm">{{
        $t('post.postComment')
      }}</span>
    </div>

    <div v-if="(comments && showComments) || showCommentForm" w:pos="relative">
      <div v-if="comments && showComments">
        <div v-for="(comment, key) in comments.value" :key="key">
          <PostCommentItem :comment="comment" />
        </div>
      </div>
      <form
        v-if="showCommentForm"
        class="sticky lg:static bottom-0 left-0 right-0 pt-4 pb-4 px-4 lg:(px-0 pb-0) -mb-10 -mr-10 -ml-10 lg:(mb-0 mr-0 ml-0)"
        @submit.prevent="submitComment"
      >
        <div w:container="~ lg:none" w:pos="relative">
          <textarea
            v-model="commentBody"
            v-autogrow
            class="input-text px-4 py-2 pr-8 text-white text-base resize-none"
            rows="1"
            :placeholder="$t('post.commentPlaceholder')"
            @keyup.enter="submitComment"
            @click.prevent="showLoginIfNotLoggedIn"
          ></textarea>
          <button
            type="submit"
            class="absolute w-3 right-3 top-2.5 max-h-3xl transform-gpu rotate-180 text-white/50 z-100"
          >
            <ArrowIcon />
          </button>
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
import {
  defineComponent,
  useAsync,
  ref,
  useContext,
  useStore,
} from '@nuxtjs/composition-api'
import { TextareaAutogrowDirective } from 'vue-textarea-autogrow-directive'
import { postApi } from '@/api/post'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'

export default defineComponent({
  components: {
    ArrowIcon,
  },
  directives: {
    autogrow: TextareaAutogrowDirective,
  },
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  emits: ['commentsLoaded'],
  setup(props, context) {
    const comments = ref([])
    const showComments = ref(false)
    const showCommentForm = ref(false)
    const commentBody = ref('')
    const { $auth } = useContext()
    const store = useStore()

    const { fetchPostsByPost, submitCommentByPost } = postApi()

    function fetchComments() {
      const newComments = useAsync(
        () => fetchPostsByPost(props.post.id),
        `comments_${props.post.id}`
      )

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
        submitCommentByPost(
          props.post.id,
          commentBody.value,
          props.post.campaign.id
        ).then(function () {
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
      showLoginIfNotLoggedIn,
    }
  },
})
</script>
