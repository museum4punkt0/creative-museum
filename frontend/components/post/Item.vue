<template>
  <div w:m="t-10">
    <div v-if="post.type !== 'system'" class="box-shadow" :class="[post.type === 'playlist' ? `highlight-bg ${textColor}` : '']">
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
import { TinyColor, readability } from '@ctrl/tinycolor';

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    },
    campaignColor: {
      type: String,
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

    const textColor = getContrastColorClass()

    function getContrastColorClass() {
      const bgColor = new TinyColor(props.campaignColor)
      const fgColor = new TinyColor('#FFFFFF')
      return readability(bgColor, fgColor) > 2 ? '!text-white' : '!text-black'
    }

    return {
      componentName,
      textColor,
      getContrastColorClass
    }
  }
})
</script>
