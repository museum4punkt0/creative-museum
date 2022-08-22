<template>
  <div>
    <h1 class="text-2xl font-bold p-7">{{ $t('pages.privacy.title') }}</h1>
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
