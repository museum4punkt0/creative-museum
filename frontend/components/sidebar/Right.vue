<template>
  <div>
    <UserNotifications v-if="$auth.loggedIn" :campaign="campaign" />
    <AwardList :campaign="campaign" />
    <BadgeList :campaign="campaign" />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
})
</script>
