<template>
  <div>
    <div v-if="campaigns" >
      <CampaignCardStack :cards="campaigns">
        <template #card="{ card }">
          <CampaignItem :campaign="card" />
        </template>
      </CampaignCardStack>
    </div>
    <div v-else>
      No Campaigns
    </div>
  </div>
</template>
<script>

import { defineComponent, useAsync } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'CampaignsPage',
  layout: 'withoutContainer',
  setup() {

    const { fetchCampaigns } = campaignApi()

    const campaigns = useAsync(() => fetchCampaigns(), 'campaigns')

    return {
      campaigns
    }

  },
})
</script>