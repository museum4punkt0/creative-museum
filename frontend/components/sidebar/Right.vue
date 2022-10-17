<template>
  <div>
    <UserNotifications v-if="$auth.loggedIn && !user" :campaign="campaign" />
    <AwardList :campaign="campaign" :user="user" />
    <BadgeList :campaign="campaign" :user="user" />
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
    user: {
      type: Object,
      default: () => {}
    }
  },
})
</script>
