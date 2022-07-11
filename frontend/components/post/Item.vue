<template>
  <div w:m="t-10">
    <div v-if="post.type !== 'system'" class="box-shadow">
      <PostHead :post="post" w:m="b-4" />
      <component :is="componentName" :post="post" w:m="b-4" />
      <PostFooter :post="post" w:m="b-4" />
      <PostComments :post="post" @commentsLoaded="refetchPostData" />
    </div>
    <div v-else class="highlight-text">
      <p>{{ post.body }}</p>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  setup(props, context) {
    const componentName = computed(() => {
      return 'PostTypes' + props.post.type.charAt(0).toUpperCase() + props.post.type.slice(1)
    })

    const refetchPostData = () => {
      context.emit('update:post', props.post.id)
    }

    return {
      componentName,
      refetchPostData
    }
  }
})
</script>
