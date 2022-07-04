<template>
  <div></div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    user: {
      type: Object,
      required: true
    }
  }
})
</script>
