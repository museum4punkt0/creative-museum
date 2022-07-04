<template>
  <div v-if="comment">{{ comment.body }}</div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    comment: {
      type: [Object, Array],
      default: () => {}
    }
  },
  setup() {

  },
})
</script>
