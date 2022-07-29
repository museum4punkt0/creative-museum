<template>
  <p w:text="right" w:mt="3" w:mr="3" class='highlight-text' :class="{'text-danger': hasError }">
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
