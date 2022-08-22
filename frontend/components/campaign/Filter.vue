<template>
  <div>
    <div
      class="snap snap-mandatory snap-x scrollbar-hide relative -mx-6 lg:mx-0 px-6 lg:px-0 flex flew-row flex-nowrap md:flex-col overflow-x-auto"
      :class="dropdownHeight ? 'h-20 lg:h-auto' : 'h-auto'"
    >
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 py-1 px-2"
        type="button"
      >
        {{ $t('filter.newest') }}
      </button>
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        type="button"
      >
        {{ $t('filter.relevant') }}
      </button>
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        type="button"
      >
        {{ $t('filter.controversial') }}
      </button>
      <DropDown
        v-if="
          campaign &&
          campaign.feedbackOptions &&
          campaign.feedbackOptions.length > 0
        "
        :options="campaign.feedbackOptions"
        label="Feedback"
        class="ml-3 lg:ml-0"
        @dropdownState="setHeight"
      />
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        type="button"
      >
        {{ $t('filter.playlist') }}
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup() {
    const dropdownHeight = ref(false)
    function setHeight(params) {
      if (params === true) {
        dropdownHeight.value = true
      } else {
        dropdownHeight.value = false
      }
    }
    return {
      dropdownHeight,
      setHeight,
    }
  },
})
</script>
