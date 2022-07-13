<template>
  <div w:m="t-10">
    <div v-if="post.type !== 'system'" class="box-shadow">
      <PostHead :post="post" w:m="b-4" />
      <component :is="componentName" :post="post" w:m="b-4" />
      <PostFooter :post="post" w:m="b-4" />
      <PostComments :post="post" @commentsLoaded="$emit('updatePost', post.id)" />
    </div>
    <div v-else class="highlight-text" w:text="center">
      <p>{{ post.body }}</p>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  emits: [
    'updatePost'
  ],
  setup(props) {
    const componentName = computed(() => {
      return 'PostTypes' + props.post.type.charAt(0).toUpperCase() + props.post.type.slice(1)
    })

    return {
      componentName
    }
  }
})
</script>
