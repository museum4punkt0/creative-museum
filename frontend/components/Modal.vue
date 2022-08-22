<template>
  <div
    class="fixed top-15 right-0 bottom-0 left-0 backdrop-filter lg:backdrop-blur-lg"
  >
    <div
      class="
        modal bg-grey text-white fixed flex flex-col flex-1 top-15 lg:top-1/2 right-0
        lg:auto left-0 lg:left-1/2 min-w-full lg:min-w-2xl lg:min-h-xl bottom-0 lg:bottom-auto rounded-xl
        lg:transform-gpu lg:-translate-x-1/2 lg:-translate-y-1/2
      "
    >
      <button
        v-if="closable"
        class="close-btn block h-4 w-4 absolute right-5 top-5 transform -translate-x-1/2 border rounded-full border-white"
        @click.prevent="$emit('closeModal')"
      />
      <slot></slot>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  onMounted,
  onUnmounted,
} from '@nuxtjs/composition-api'
export default defineComponent({
  props: {
    closable: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['closeModal'],
  setup() {
    onMounted(() => {
      if (process.client) {
        const body = document.querySelector('body')
        body.style.height = '100vh'
      }
    })
    onUnmounted(() => {
      if (process.client) {
        const body = document.querySelector('body')
        body.style.height = 'auto'
      }
    })
  },
})
</script>
