<template>
  <div
    class="shadow-md shadow-black/50 h-xl lg:h-3xl py-6 px-4 rounded-lg block"
    :style="`background-color: ${campaign.color}`"
  >
    <article class="flex flex-col h-full" :class="`text-${textColor}`">
      <header>
        <div class="text-right">
          <span class="border border-current rounded-full py-1 px-2">
            {{ $t('duration') }}:
            {{ $dayjs(campaign.start).format('DD.MM.YYYY') }}
            <template v-if="campaign.end && campaign.end !== campaign.start">
              – {{ $dayjs(campaign.end).format('DD.MM.YYYY') }}
            </template>
          </span>
        </div>
        <h1 v-if="campaign.title" class="my-4 text-xl lg:text-xxl">
          <NuxtLink :to="localePath(`/campaigns/${campaign.id}`)">
            {{ campaign.title }}
          </NuxtLink>
        </h1>
      </header>
      <div v-if="campaign.shortDescription">
        {{ campaign.shortDescription }}
      </div>
      <footer v-if="campaign.partners.length > 0" class="mt-auto">
        {{ $t('campaign.partner') }}
        <div class="flex flex-row flex-wrap">
          <div v-for="(partner, key) in campaign.partners" :key="key" class="mt-6">
            <img
              v-if="partner.logo"
              :src="`${backendUrl}/${partner.logo.contentUrl}`"
              :data-url="`${backendUrl}/${partner.logo.contentUrl}`"
              :alt="partner.title"
              class="max-w-40"
            />
            <p v-else>
              {{ partner.title }}
            </p>
          </div>
        </div>
      </footer>
    </article>
  </div>
</template>
<script>
import { TinyColor, readability } from '@ctrl/tinycolor'
import { defineComponent, useContext } from '@nuxtjs/composition-api'
export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const textColor = getContrastColorClass()
    const context = useContext()

    function getContrastColorClass() {
      const bgColor = new TinyColor(props.campaign.color)
      const fgColor = new TinyColor('#FFFFFF')
      return readability(bgColor, fgColor) > 2 ? 'white' : 'black'
    }

    return {
      textColor,
      getContrastColorClass,
      backendUrl: context.$config.backendUrl
    }
  },
})
</script>
