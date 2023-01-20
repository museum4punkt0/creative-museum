<template>
  <div>
    <div>
      <div
        v-if="campaigns && campaigns.length > 0"
        class="px-container-padding"
      >
        <h1 class="sr-only">{{ $t('campaigns.headline') }}</h1>
        <CampaignStack :campaigns="campaigns" />
      </div>
      <div v-else-if="campaigns && campaigns.length === 0">No Campaigns</div>
      <div v-else>
        <div class="container text-center min-h-2xl relative">
          <UtilitiesLoadingIndicator
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  useStore,
  ref,
  useContext,
  onMounted,
  useMeta
} from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'IndexPage',
  layout: 'WithoutContainer',
  auth: false,
  setup() {
    const store = useStore()
    const { $auth, i18n } = useContext()
    const { fetchCampaigns } = campaignApi()
    const campaigns = ref(null)

    onMounted(async () => {
      campaigns.value = await fetchCampaigns()
    })

    useMeta({
      title: i18n.t('pages.index.title') + ' | ' + i18n.t('pageTitle')
    })

    $auth.$storage.removeState('campaignScore')

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    return {
      campaigns,
    }
  },
  head: {}
})
</script>
