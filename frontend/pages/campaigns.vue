<template>
  <div>
    <div v-if="campaigns.length > 0">
      {{ campaigns }}
    </div>
    <div v-else>
      No Campaigns
    </div>
  </div>
</template>
<script>

import { defineComponent, ref, useFetch, useContext } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  setup() {

    const { fetchCampaigns } = campaignApi()
    const campaigns = ref([])

    useFetch(async () => {
      campaigns.value = await fetchCampaigns()
    })

    return {
      campaigns
    }

  },
})
</script>