<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="flex-1 overflow-x-hidden page-inner">
      <div class="bg-grey shadow-lg shadow-black/20 sticky z-10 top-0">
        <PageHeader />
      </div>
      <div class="container px-6 lg:pt-20">
        <div class="pb-safe">
          <Nuxt />
        </div>
      </div>
    </div>
    <OverlaysWrapper />
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
  }
})
</script>
