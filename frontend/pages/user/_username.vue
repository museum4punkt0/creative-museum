<template>
  <div>User Profile</div>
</template>
<script>
import { defineComponent, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()
    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)
  },
})
</script>
