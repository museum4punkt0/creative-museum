<template>
  <div>
    <div class="page-header p-6">
      <NuxtLink :to="localePath('/')" class="back-btn">{{
        $t('tutorial.firstSteps')
      }}</NuxtLink>
    </div>
    <div
      class="box-shadow-mobile relative m-6 lg:m-0 p-6 text-center"
    >
      HALLO WELT
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    award: {
      type: Object,
      required: true
    }
  },
  setup() {

  },
})
</script>
