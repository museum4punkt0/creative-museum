<template>
  <div>
    <div v-if="comments" class="relative">
      <ul>
        <li v-for="(comment, key) in comments" :key="key">
          <PostCommentItem :comment="comment" :post-type="post.type" />
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const comments = ref([])

    const { fetchPostsByPost } = postApi()

    async function fetchComments() {
      comments.value = await fetchPostsByPost(props.post.id)
    }

    fetchComments()

    return {
      comments,
      fetchComments
    }
  },
})
</script>
