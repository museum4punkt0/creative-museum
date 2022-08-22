<template>
  <div
    class="box-shadow px-4 py-2 rounded-full flex flex-row items-end justify-center"
  >
    <span class="text-2xl mr-2">{{
      campaignScore && campaignScore.value && campaignScore.value.score
        ? Math.abs(campaignScore.value.score).toLocaleString()
        : '0'
    }}</span>
    <span>{{ $t('user.points') }}</span>
  </div>
</template>
<script>
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const context = useContext()
    return {
      campaignScore: computed(() =>
        context.$auth.$storage.getState('campaignScore')
      ),
    }
  },
})
</script>
