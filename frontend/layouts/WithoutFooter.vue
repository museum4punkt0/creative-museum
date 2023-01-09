<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="flex-1 overflow-x-hidden page-inner">
      <div class="bg-grey shadow-lg shadow-black/20 sticky z-10 top-0">
        <PageHeader />
      </div>
      <div w:container="~" w:p="6">
        <Nuxt />
      </div>
    </div>
    <OverlaysWrapper />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'WithoutFooter',
})
</script>
