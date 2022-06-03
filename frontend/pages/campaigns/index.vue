<template>
  <div>
    <div v-if="campaigns">
      {{ campaigns }}
      <div v-for="(campaign, key) in campaigns" :key="key">
        <NuxtLink :to="`/campaigns/${campaign.id}`">Link</NuxtLink>
      </div>
    </div>
    <div v-else>
      No Campaigns
    </div>
  </div>
</template>
<script>

import { defineComponent, ref, useAsync } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'CampaignsPage',
  setup() {

    const { fetchCampaigns } = campaignApi()

    const campaigns = useAsync(() => fetchCampaigns(), 'campaigns')

    return {
      campaigns
    }

  },
})
</script>