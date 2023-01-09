<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="overflow-x-hidden page-inner">
      <div class="bg-grey shadow-lg shadow-black/20 sticky z-10 top-0">
        <PageHeader />
      </div>
      <Nuxt />
      <PageFooter />
    </div>
    <OverlaysWrapper />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'WithoutContainer',
})
</script>
