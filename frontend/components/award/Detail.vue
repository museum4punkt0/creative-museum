<template>
  <div>
    <div class="page-header p-6">
      <a class="back-btn" @click.prevent="$emit('closeAwardDetail')">{{
        $t('awards.detailHeadline')
      }}</a>
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
  emits: ['closeAwardDetail'],
  setup() {

  },
})
</script>
