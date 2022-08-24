<template>
  <div>
    <h1 class="text-primary p-7 text-2xl font-bold">Login callback</h1>
    <nuxt-link to="/sample">Sample</nuxt-link>
  </div>
</template>
<script>
import { defineComponent, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  middleware: 'auth',
  setup() {
    const store = useStore()
    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)
  },
})
</script>
