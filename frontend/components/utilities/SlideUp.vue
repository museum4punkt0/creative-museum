<template>
  <div>
    <div
      class="fixed top-0 left-0 right-0 bottom-0 pointer-events-none touch-none z-30"
    ></div>
    <div
      class="fixed top-0 right-0 bottom-0 left-0 backdrop-filter lg:backdrop-blur-lg z-40 lg:z-100"
    >
      <div
        class="slideup bg-grey text-white fixed flex flex-col flex-1 right-2 lg:right-auto left-2 lg:left-1/2 pb-4 lg:pb-0 lg:min-w-2xl lg:min-h-xl bottom-0 lg:bottom-auto lg:top-1/2 rounded-xl transform-gpu lg:-translate-x-1/2 lg:-translate-y-1/2"
      >
        <button
          v-if="closable"
          class="close-btn block h-4 w-4 absolute right-4 top-5 transform -translate-x-1/2 border rounded-full border-white rotate-45"
          @click.prevent="$emit('closeModal')"
        ></button>
        <div
          class="overflow-y-scroll max-height-without-header lg:h-full flex flex-col flex-1"
        >
          <slot />
        </div>
      </div>
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
        body.classList.add('modal-open')
      }
    })
    onUnmounted(() => {
      if (process.client) {
        const body = document.querySelector('body')
        body.classList.remove('modal-open')
      }
    })
  },
})
</script>
