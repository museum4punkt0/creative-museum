<template>
  <div>
    <div>
      <div
        v-if="campaigns && campaigns.length > 0"
        class="px-container-padding"
      >
        <CampaignStack :campaigns="campaigns" />
      </div>
      <div v-else-if="campaigns && campaigns.length === 0">No Campaigns</div>
      <div v-else>
        <div class="container text-center min-h-2xl relative">
          <UtilitiesLoadingIndicator class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" />
        </div>
      </div>
    </div>
    <Modal v-if="tutorialOpen">
      <Tutorial @closeModal="tutorialOpen = false" />
    </Modal>
  </div>
</template>
<script>
import {
  defineComponent,
  useStore,
  ref,
  useContext,
  onMounted,
  watch
} from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'IndexPage',
  layout: 'WithoutContainer',
  auth: false,
  setup() {

    const store = useStore()
    const tutorialOpen = ref(false)
    const { $auth } = useContext()
    const { fetchCampaigns } = campaignApi()
    const campaigns = ref(null)

    onMounted(async () => {
      campaigns.value = await fetchCampaigns()
    })

    $auth.$storage.removeState('campaignScore')

    if ($auth.loggedIn && !$auth.user.tutorial && $auth.user.username) {
      tutorialOpen.value = true
    }

    $auth.$storage.watchState('user.username', _ => {
      tutorialOpen.value = true
    })

    store.dispatch('hideAddButton')

    return {
      tutorialOpen,
      campaigns,
    }
  },
})
</script>
