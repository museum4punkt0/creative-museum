<template>
  <div>
    <NuxtLink :to="`/campaigns/${campaign.id}`">Link</NuxtLink>
  </div>
</template>
<script>
import { defineComponent } from '@vue/composition-api'

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
