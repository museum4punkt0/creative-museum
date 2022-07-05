<template>
  <div>

    <div
      class="highlight-text"
      w:cursor="pointer"
      w:text="sm"
    >
      <span v-if="post.comments && post.commentCount > 2" @click.prevent="fetchComments"> {{ post.commentCount }} {{ $t('post.showComments') }}</span>
      <span v-else @click.prevent="postComment">{{ $t('post.postComment') }}</span>
    </div>

    <div v-if="comments">
      <div v-for="(comment, key) in comments.value" :key="key">
        <PostCommentItem :comment="comment" />
      </div>
    </div>
    <div v-else>
      <div v-for="(comment, key) in post.comments" :key="key">
          <PostCommentItem :comment="comment" />
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useAsync, ref } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  emits:['closeModal'],
  setup(props, context) {
    const comments = ref(null)
    const { fetchPostsByPost } = postApi()

    function fetchComments() {
      comments.value = useAsync(() => fetchPostsByPost(props.post.id), `comments_${props.post.id}`)
    }

    function postComment() {
      context.emit('postComment')
    }

    return {
      comments,
      fetchComments,
      postComment
    }
  }
})
</script>
