<template>
  <div w:m="t-10">
    <div v-if="post.type !== 'system'" class="box-shadow">
      <PostHead :post="post" w:m="b-4" />
      <component :is="componentName" :post="post" w:m="b-4" />
      <PostFooter :post="post" />
      <PostComments :post="post" />
    </div>
    <div v-else class="highlight-text">
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
  setup(props) {
    const componentName = computed(() => {
      return 'PostTypes' + props.post.type.charAt(0).toUpperCase() + props.post.type.slice(1)
    })
    return {
      componentName
    }
  },
})
</script>
