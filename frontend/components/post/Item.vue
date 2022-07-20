<template>
  <div w:m="t-10">
    <div v-if="post.type !== 'system'" class="box-shadow">
      <PostHead :post="post" w:m="b-4" @toggle-bookmark-state="$emit('toggle-bookmark-state', post.id)" />
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
import { defineComponent, computed } from '@nuxtjs/composition-api'

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
      return props.post.type ? 'PostTypes' + props.post.type.charAt(0).toUpperCase() + props.post.type.slice(1) : false
    })

    return {
      componentName
    }
  }
})
</script>
