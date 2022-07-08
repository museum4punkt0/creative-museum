<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="" w:overflow="x-hidden">
      <div
        w:bg="grey"
        w:shadow="lg black/20"
      >
        <PageHeader />
      </div>
      <Nuxt/>
      <PageFooter />
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'WithoutContainer'
})
</script>

