<template>
  <div>

    <span @click.prevent="fetchComments">Fetch</span>

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
  setup(props) {
    const comments = ref(null)
    const { fetchPostsByPost } = postApi()

    function fetchComments() {
      comments.value = useAsync(() => fetchPostsByPost(props.post.id), `comments_${props.post.id}`)
    }

    return {
      comments,
      fetchComments
    }
  }
})
</script>
