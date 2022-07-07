<template>
  <div
    w:px="4"
    w:py="2"
    w:rounded="full"
    w:flex="~ row"
    w:align="items-end"
    w:justify="center"
    class="box-shadow"
  >
    <span w:text="2xl" w:mr="2">{{campaignScore && campaignScore.value && campaignScore.value.score ? Math.abs(campaignScore.value.score).toLocaleString() : '0' }}</span>
    <span>{{ $t('user.points') }}</span>
  </div>
</template>
<script>
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const context = useContext()
    return {
      campaignScore: computed(() => context.$auth.$storage.getState('campaignScore'))
    }
  },
})
</script>
