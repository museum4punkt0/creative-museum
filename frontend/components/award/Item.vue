<template>
  <div class="flex flex-row items-center mb-2" @click.prevent="awardDetailOpen = true">
    <div
      class="w-18 h-18 rounded-full mr-3 overflow-hidden flex-shrink-0"
    >
      <img
        :src="`${backendUrl}/${award.picture.contentUrl}`"
        :alt="award.title"
        class="max-w-18 h-auto"
      />
    </div>
    <div class="flex flex-col flex-grow">
      <p class="mb-1">{{ award.description }}</p>
      <p class="highlight-text text-sm">{{ award.price.toLocaleString() + ' ' + $t('points') }}</p>
      <button class="btn-outline self-start mt-2 text-xs p-1" type="button">
        {{ $t('awards.gift') }}
      </button>
    </div>
    <Modal v-if="awardDetailOpen" :closable="true" @closeModal="awardDetailOpen = false">
      <AwardDetail :award="award" />
    </Modal>
  </div>
</template>
<script lang="ts">
import { defineComponent, useContext, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    award: {
      type: Object,
      required: true
    }
  },
  setup() {
    const context = useContext()
    const awardDetailOpen = ref(false)

    return {
      awardDetailOpen,
      backendUrl: context.$config.backendUrl
    }
  }
})
</script>
>


</script>