<template>
  <div>
    <div>
      <div
        v-if="campaigns && campaigns.length > 0"
        class="px-container-padding"
      >
        <CampaignStack :campaigns="campaigns" />
      </div>
      <div v-else>No Campaigns</div>
    </div>
    <Modal v-if="tutorialOpen">
      <Tutorial @closeModal="tutorialOpen = false" />
    </Modal>
  </div>
</template>
<script>
import {
  defineComponent,
  computed,
  useStore,
  ref,
  useContext,
  onMounted,
} from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'IndexPage',
  layout: 'WithoutContainer',
  auth: false,
  setup() {

    const store = useStore()
    const user = computed(() => store.state.auth.user)
    const tutorialOpen = ref(false)
    const { $auth } = useContext()
    const { fetchCampaigns } = campaignApi()
    const campaigns = ref(null)

    onMounted(async () => {
      campaigns.value = await fetchCampaigns()
    })

    $auth.$storage.removeState('campaignScore')

    if (user.value !== null && !user.value.tutorial && user.value.username) {
      tutorialOpen.value = true
    }

    store.dispatch('hideAddButton')

    return {
      tutorialOpen,
      campaigns,
    }
  },
})
</script>
