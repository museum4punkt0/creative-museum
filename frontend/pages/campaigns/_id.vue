<template>
  <div>
    <div v-if="campaign">
      {{ campaign }}
    </div>
    <div v-else>
      No Campaign found
    </div>

  </div>
</template>

<script>

import { defineComponent, useAsync, useRoute } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'CampaignPage',
  setup() {

    const route = useRoute()

    const { fetchCampaign } = campaignApi()

    let campaign = null

    if (route.value.params.id) {
      campaign = useAsync(() => fetchCampaign(route.value.params.id), `campaign-${route.value.params.id}`)
    }

    return {
      campaign
    }

  },
})
</script>