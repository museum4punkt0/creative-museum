<template>
  <div>
    <SlideUp v-if="showLogin" :closable="true" @closeModal="showLogin = false"> LOGIN! </SlideUp>
  </div>
</template>
<script>
import { defineComponent, useStore, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()
    const showLogin = computed({
      get() { return store.state.showLogin },
      set() {
        store.dispatch('hideLogin')
      }
    })
    return {
      showLogin,
    }
  },
})
</script>
