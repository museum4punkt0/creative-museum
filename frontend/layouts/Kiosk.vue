<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="flex-1 overflow-x-hidden page-inner">
      <Nuxt />
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'LayoutDefault',
  setup() {
    if (process.client) {
      window.addEventListener('sw-installed', () => {
        console.log('sw-installed from layout setup')
      })
      window.addEventListener('sw-updated', () => {
        console.log('sw-updated from layout setup')
      })
    }
  },
  head() {
      return {
          htmlAttrs: {
              lang: this.$i18n.locale
          }
      }
  },
})
</script>
