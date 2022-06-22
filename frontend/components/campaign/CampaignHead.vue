<template>
  <div>
    {{ campaign.title }}
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
