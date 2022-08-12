<template>
  <div w:w="4" w:h="5" w:pos="relative">
    <span
      w:pos="absolute"
      w:left="1/2"
      w:top="0"
      w:display="block"
      w:h="0.75"
      w:w="0.75"
      :class="`bg-${textColor}`"
      w:border="rounded-full"
    ></span>
    <span
      w:pos="absolute"
      w:left="1/2"
      w:top="2"
      w:display="block"
      w:h="0.75"
      w:w="0.75"
      :class="`bg-${textColor}`"
      w:border="rounded-full"
    ></span>
    <span
      w:pos="absolute"
      w:left="1/2"
      w:top="4"
      w:display="block"
      w:h="0.75"
      w:w="0.75"
      :class="`bg-${textColor}`"
      w:border="rounded-full"
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
