<template>
  <div>
    <div v-if="campaigns.length > 0" class="px-container-padding" >
      <CampaignCardStack :cards="campaigns" />
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