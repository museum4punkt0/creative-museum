<template>
  <div>
    <h1 class="page-header" w:m="t-0">{{ campaign.title }}</h1>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true
    }
  },
  setup() {

  },
})
</script>
