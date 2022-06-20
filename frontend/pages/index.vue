<template>
  <div>
    <div>
      <div v-if="campaigns.length > 0" class="px-container-padding" >
        <CampaignCardStack :cards="campaigns" />
      </div>
      <div v-else>
        No Campaigns
      </div>
    </div>
    <Modal v-if="tutorialOpen">
      <Tutorial @closeModal="tutorialOpen = false" />
    </Modal>
  </div>
</template>
<script>
import { defineComponent, useAsync, computed, useStore, ref } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'

export default defineComponent({
  name: 'IndexPage',
  layout: 'WithoutContainer',
  setup() {
    const store = useStore()
    const user = computed(() => store.state.auth.user);
    const tutorialOpen = ref(false)


    const { fetchCampaigns } = campaignApi()

    const campaigns = useAsync(() => fetchCampaigns(), 'campaigns')

    if (user.value !== null && !user.value.tutorial) {
      tutorialOpen.value = true
    }

    return {
      tutorialOpen,
      campaigns
    }

  }
})
</script>