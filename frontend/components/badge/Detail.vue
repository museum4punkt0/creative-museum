<template>
  <div :style="styleAttr" class="flex flex-col flex-1 justify-between h-full">
    <div class="page-header px-6 self-start">
      <a class="back-btn" @click.prevent="$emit('closebadgeDetail')">
        {{ $t('badges.detailHeadline') }}
      </a>
    </div>
    <div class="flex-1">
      <div class="box-shadow relative m-6 ">
        <img
          v-if="badge.picture"
          :src="`${backendUrl}/${badge.picture.contentUrl}`"
          :alt="badge.title"
          class="h-40 w-auto mx-auto"
        />
        <h1 class="text-2xl">{{ badge.title }}</h1>
        <p>{{ badge.description }}</p>
      </div>
    </div>
    <div class="mx-6 mb-6">
      <button
        v-if="badge.available && !badge.taken"
        class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
        @click.prevent="mode = 'giveaway'"
      >
        {{ $t('badges.giftbadge') }}
      </button>
      <button class="btn-outline w-full" @click.prevent="$emit('closebadgeDetail')">{{ $t('close') }}</button>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext, ref, computed } from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'

export default defineComponent({
  props: {
    badge: {
      type: Object,
      required: true
    }
  },
  emits: ['closebadgeDetail'],
  setup(props) {

    const context = useContext()

    const bgColor = new TinyColor(props.badge.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')

    const campaignContrastColor = computed(() => {
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.badge.campaign.color}; --highlight-contrast: ${campaignContrastColor.value};`
    })
    return {
      backendUrl: context.$config.backendUrl,
      styleAttr
    }

  },
})
</script>
