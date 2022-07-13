<template>
  <div
    w:pos="fixed"
    w:top="15 lg:0"
    w:right="0"
    w:bottom="0"
    w:left="0"
    w:backdrop="~ lg:blur-lg"
    w:z="40 lg:100"
  >
    <div
      class="modal"
      w:bg="grey"
      w:pos="fixed"
      w:top="15 lg:1/2"
      w:right="0 lg:auto"
      w:left="0 lg:1/2"
      w:min-w="full lg:2xl"
      w:min-h="full lg:xl"
      w:bottom="0 lg:auto"
      w:border="rounded-xl"
      w:transform="lg:~ lg:-translate-x-1/2 lg:-translate-y-1/2"
    >
      <button
        v-if="closable"
        class="close-btn"
        w:display="block"
        w:h="4"
        w:w="4"
        w:pos="absolute"
        w:right="5"
        w:top="5"
        w:transform="-translate-x-1/2"
        w:border="~ rounded-full white"
        @click.prevent="$emit('closeModal')" />
      <slot></slot>
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    closable: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    'closeModal'
  ]
})
</script>
<style lang="postcss" scoped>
.close-btn {
  box-shadow: 0 0 1px 0 theme('colors.white') inset,
    0 0 1px 0 theme('colors.white');
  @apply transform duration-200 ease-in-out -translate-x-1/2 rotate-45
    before:(block content-[''] w-px h-3 bg-white absolute top-1/2 transform -translate-x-px  translate-x-[-.5px] left-1/2 -translate-y-1/2)
    after:(block content-[''] w-3 h-px bg-white absolute top-1/2 transform translate-y-[-.5px] left-1/2 -translate-x-1/2 );
  &:hover {
    @apply -rotate-135 scale-125;
  }
}
</style>
