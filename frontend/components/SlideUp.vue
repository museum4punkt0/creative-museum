<template>
  <div
    w:pos="fixed"
    w:top="0"
    w:right="0"
    w:bottom="0"
    w:left="0"
    w:z="40 lg:100"
  >
    <div
      class="modal"
      w:bg="grey"
      w:pos="fixed"
      w:flex="~ col 1"
      w:right="0 lg:auto"
      w:left="0 lg:1/2"
      w:min-w="full lg:2xl"
      w:min-h="full lg:xl"
      w:bottom="0"
      w:top="auto"
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
        @click.prevent="$emit('closeSlideUp')"
      />
      <slot></slot>
    </div>
  </div>
</template>
<script>
import { defineComponent, onMounted, onUnmounted } from '@nuxtjs/composition-api'
export default defineComponent({
  props: {
    closable: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    'closeSlideUp'
  ],
  setup() {
    onMounted(() => {
      if (process.client) {
        const body = document.querySelector('body')
        window.scroll({
          top: 0,
          left: 0,
          behavior: 'smooth'
        })
        body.style.height ='100vh'
      }
    });
    onUnmounted(() => {
      if (process.client) {
        const body = document.querySelector('body')
        body.style.height = 'auto'
      }
    })
  }
})
</script>
