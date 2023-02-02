<template>
  <p class="highlight-text text-right">
    {{ maxCount - count }} / {{ maxCount }}
  </p>
</template>

<script>
import { defineComponent, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    count: {
      type: Number,
      required: true,
    },
    maxCount: {
      type: Number,
      required: true,
    },
  },
  setup(props) {
    const hasError = computed(() => props.count > props.maxCount)

    return {
      hasError,
    }
  },
})
</script>
