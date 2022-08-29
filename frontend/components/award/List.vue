<template>
  <div v-if="availableAwards || unavailableAwards">
    <div class="flex flex-row justify-between mb-10">
      <span class="text-2xl">{{ $t('campaign.awards') }}</span>
    </div>
    <div v-if="availableAwards" class="mb-6">
      <div class="text-$highlight text-sm mb-2">{{ $t('awards.available') }}</div>
      <AwardItem v-for="(award, key) in availableAwards" :key="key" :award="award" />
    </div>
    <div v-if="unavailableAwards" class="mb-6">
      <div class="text-$highlight text-sm mb-2">{{ $t('awards.unavailable') }}</div>
      <AwardItem v-for="(award, key) in unavailableAwards" :key="key" :award="award" />
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {}
    }
  },
  setup(props) {
    const { $auth }:any = useContext()
    const unavailableAwards:any = []
    const availableAwards = computed(() => {
      if ($auth.loggedIn && props.campaign && 'awards' in props.campaign) {
        return props.campaign.awards.filter(function (item: any) {
          const currentCampaign = $auth.user.memberships.filter((membership: any) => {
            return membership.campaign.id === props.campaign.id
          })
          if (currentCampaign[0].score > item.price) {
            return true
          } else {
            unavailableAwards.push(item)
            return false
          }
        })
      }
    })

    return {
      availableAwards,
      unavailableAwards
    }

  }
})
</script>
