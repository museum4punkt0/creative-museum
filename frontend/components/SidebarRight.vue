<template>
  <div>
    <div class="mb-12">
      <UserNotifications :campaign="campaign" />
    </div>
    <div class="mb-12">
      <AwardList :campaign="campaign" />
    </div>
    <div class="mb-12">
      <BadgeList :campaign="campaign" />
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {}
    }
  },
})
</script>

