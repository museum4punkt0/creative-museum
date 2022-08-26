<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="flex-1 overflow-y-auto overflow-x-hidden">
      <div class="bg-grey shadow-lg shadow-black/20 sticky z-10 top-0">
        <PageHeader />
      </div>
      <div class="container p-6 lg:pt-20">
        <Nuxt @noPosts="console.log('no posts')" />
      </div>
    </div>
    <GlobalLogin />
  </div>
</template>
<script>
import { defineComponent, computed, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'LayoutDefault',
})
</script>
