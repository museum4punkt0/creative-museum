<template>
  <div>
    <div w:pos="relative" w:m="-x-6 lg:0" w:p="x-6 lg:0" :w:h="dropdownHeight ? '20 lg:auto' : 'auto'" w:flex="~ row nowrap md:col" w:snap="x" w:overflow="x-auto" class="snap snap-mandatory snap-x scrollbar-hide">
      <button w:px="2" w:py="1" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Neuste
      </button>
      <button w:px="2" w:py="1" w:ml="3 lg:0" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Relevant
      </button>
      <button w:px="2" w:py="1" w:ml="3 lg:0" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Kontrovers
      </button>
      <DropDown v-if="campaign && campaign.feedbackOptions && campaign.feedbackOptions.length > 0" w:ml="3 lg:0" :options="campaign.feedbackOptions" label="Feedback" @dropdownState="setHeight" />
      <button w:px="2" w:py="1" w:ml="3 lg:0" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Playlist
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {}
    }
  },
  setup() {
    const dropdownHeight = ref(false)
    function setHeight(params) {
      if (params === true)  {
        dropdownHeight.value = true
      } else {
        dropdownHeight.value = false
      }
    }
    return {
      dropdownHeight,
      setHeight
    }
  },
})
</script>
