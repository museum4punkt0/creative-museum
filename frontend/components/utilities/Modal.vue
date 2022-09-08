<template>
  <div>
    <div class="fixed top-0 left-0 right-0 bottom-0 pointer-events-none touch-none z-30"></div>
    <div
      class="fixed top-14 right-0 bottom-0 left-0 backdrop-filter lg:backdrop-blur-lg z-40"
    >
      <div
        class="
          modal bg-grey text-white fixed flex flex-col flex-1 top-14 lg:top-1/2 right-0
          lg:auto left-0 lg:left-1/2 min-w-full lg:min-w-2xl min-h-full lg:min-h-xl bottom-0 lg:bottom-auto rounded-xl
          lg:transform-gpu lg:-translate-x-1/2 lg:-translate-y-1/2
        "
      >
        <button
          v-if="closable"
          class="close-btn block h-4 w-4 absolute right-5 top-5 transform -translate-x-1/2 border rounded-full border-white rotate-45"
          type="button"
          @click.prevent="$emit('closeModal')"
        ></button>
        <div class="overflow-y-scroll pb-safe max-height-without-header lg:h-full flex flex-col flex-1">
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
