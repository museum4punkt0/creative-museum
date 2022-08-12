<template>
  <div>
    <h1 w:text="primary 2xl bold" w:p="7">{{ $t('pages.privacy.title') }}</h1>
  </div>
</template>
<script>
import { defineComponent, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()
    store.dispatch('hideAddButton')
  },
})
</script>
