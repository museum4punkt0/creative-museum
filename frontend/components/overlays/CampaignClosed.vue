<template>
  <UtilitiesSlideUp
    v-if="showCampaignClosed"
    :closable="true"
    @closeModal="showCampaignClosed = false"
  >
    <div class="flex flex-col flex-1 h-full justify-between">
      <div class="page-header px-6">
        <a class="back-btn" @click.prevent="showCampaignClosed = false">
          {{ $t('globalCampaignClosed.header') }}</a
        >
      </div>
      <div class="p-6">
        <p class="mb-6">
          {{ $t('globalCampaignClosed.text') }}
        </p>
        <button class="btn-outline w-full" @click.prevent="showCampaignClosed = false">
          {{ $t('close') }}
        </button>
      </div>
    </div>
  </UtilitiesSlideUp>
</template>
<script>
import { defineComponent, useStore, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()
    const showCampaignClosed = computed({
      get() {
        return store.state.showCampaignClosed
      },
      set() {
        store.dispatch('hideCampaignClosed')
      },
    })
    return {
      showCampaignClosed,
    }
  },
})
</script>
