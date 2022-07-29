<template>
  <p w:text="right" class='highlight-text'>
    {{ maxCount - text.length }} / {{maxCount}}
  </p>
</template>

<script>
import { defineComponent, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    text: {
      type: String,
      required: true
    },
    maxCount: {
      type: Number,
      required: true
    }
  },
  setup(props) {

    const hasError = computed(() => props.text.length > props.maxCount)

    return {
      hasError
    }
  },
})
</script>
