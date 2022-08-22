<template>
  <div class="relative w-4 h-5">
    <span
      class="absolute left-1/2 top-0 block h-0.75 w-0.75 rounded-full"
      :class="`bg-${textColor}`"
    ></span>
    <span
      class="absolute left-1/2 top-2 block h-0.75 w-0.75 rounded-full"
      :class="`bg-${textColor}`"
    ></span>
    <span
      class="absolute left-1/2 top-4 block h-0.75 w-0.75 rounded-full"
      :class="`bg-${textColor}`"
    ></span>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    textColor: {
      type: String,
      required: true,
    },
  },
})
</script>
