<template>
  <div
    class="highlight-bg"
    w:rounded="full"
    w:w="7"
    w:h="7"
  >
    <img v-if="user" />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    user: {
      type: Object,
      default: () => {}
    }
  }
})
</script>
