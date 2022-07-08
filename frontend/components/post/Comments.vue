<template>
  <div>
    <div
      class="highlight-text"
      w:cursor="pointer"
      w:text="sm"
    >
      <ArrowIcon w:pos="relative" w:m="r-0.5" w:display="inline-block" />
      <span v-if="post.comments && post.commentCount > 0" @click.prevent="fetchComments"> {{ $t('post.showComments', { count: post.commentCount }) }}</span>
      <span v-else @click.prevent="showCommentForm = true">{{ $t('post.postComment') }}</span>
    </div>

    <div v-if="comments">
      <div v-for="(comment, key) in comments.value" :key="key">
        <PostCommentItem :comment="comment" />
      </div>
    </div>

    <div v-if="showCommentForm" w:m="t-4">
      <textarea class="input-text" w:p="x-4 y-2" w:text="xs"></textarea>
    </div>
  </div>
</template>
<script>
import { defineComponent, useAsync, ref } from '@nuxtjs/composition-api'
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
  setup(props) {
    const comments = ref(null)
    const showCommentForm = ref(false)
    const { fetchPostsByPost } = postApi()

    function fetchComments() {
      comments.value = useAsync(() => fetchPostsByPost(props.post.id), `comments_${props.post.id}`)
      showCommentForm.value = true
    }

    return {
      comments,
      showCommentForm,
      fetchComments
    }
  }
})
</script>
