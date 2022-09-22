<template>
  <div
    class="box-shadow px-4 py-2 rounded-full flex flex-row items-end justify-center"
  >
    <span class="text-2xl mr-2">{{
      Math.abs(campaignScore).toLocaleString()
    }}</span>
    <span>{{ $t('points') }}</span>
  </div>
</template>
<script>
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const { $auth } = useContext()

    const campaignScore = computed(() => {
      if (!$auth.loggedIn || !$auth.user.hasOwnProperty('memberships')) {
        return 0
      }
      for (const id in $auth.user.memberships) {
        const membership = $auth.user.memberships[id]
        if (membership.campaign.id !== props.campaign.id) {
          continue
        }
        return membership.score
      }
      return 0
    })

    return {
      campaignScore,
    }
  },
})
</script>
