<template>
  <div>
    Update your profile
  </div>
</template>
<script>
import { defineComponent, useStore } from '@nuxtjs/composition-api'
export default defineComponent({
  name: 'UserUpdate',
  setup() {
    const store = useStore()
    store.dispatch('hideAddButton')
  },
})
</script>