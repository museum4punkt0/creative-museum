<template>
  <div :style="styleAttr" class="flex flex-col justify-between h-full">
    <div v-if="!showGift">
      <div class="page-header p-6">
        <a class="back-btn" @click.prevent="$emit('closeAwardDetail')">{{
          $t('awards.detailHeadline')
        }}</a>
      </div>
      <div
        class="box-shadow-mobile relative m-6 lg:m-0 p-6"
      >
        <img
          v-if="award.picture"
          :src="`${backendUrl}/${award.picture.contentUrl}`"
          :alt="award.title"
          class="w-full max-w-32 h-auto mx-auto"
        />
        <h1 class="text-2xl">{{ award.title }}</h1>
        <p>{{ award.description }}</p>
        <div class="text-$highlight">{{ award.price.toLocaleString() + ' ' + $t('points') }} </div>
      </div>
    </div>
    <div v-else>
      <div class="page-header p-6">
        <a class="back-btn" @click.prevent="showGift = false">{{
          $t('selectProfile')
        }}</a>
      </div>
      <input type="text" class="input-text" />
      <ul>
        <li>Liste</li>
      </ul>
    </div>
    <div v-if="!showGift" class="mx-6 mb-6">
      <button class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4" @click.prevent="showGift = true">{{ $t('awards.giftAward') }}</button>
      <button class="btn-outline w-full" @click.prevent="$emit('closeAwardDetail')">{{ $t('close') }}</button>
    </div>
    <div v-else class="mx-6 mb-6">
      <button class="btn-outline w-full" @click.prevent="submitAward">{{ $t('confirmSelection') }}</button>
    </div>
  </div>
</template>
<script>
import { TinyColor, readability } from '@ctrl/tinycolor'
import { defineComponent, useContext, computed, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    award: {
      type: Object,
      required: true
    }
  },
  emits: ['closeAwardDetail'],
  setup(props) {
    const context = useContext()

    const showGift = ref(false)

    const bgColor = new TinyColor(props.award.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')

    const campaignContrastColor = computed(() => {
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.award.campaign.color}; --highlight-contrast: ${campaignContrastColor.value};`
    })

    function submitAward() {
      console.log('Submit Award')
    }

    return {
      showGift,
      styleAttr,
      backendUrl: context.$config.backendUrl,
      submitAward
    }

  },
})
</script>
