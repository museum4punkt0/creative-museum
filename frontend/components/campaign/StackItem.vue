<template>
  <NuxtLink
    :to="localePath('/campaigns/' + campaign.id)"
    class="shadow-md shadow-black/50 h-xl lg:h-3xl py-6 px-4 rounded-2xl block lg:pointer-events-none focus:outline-none focus-visible:(shadow-lg shadow-black/75)"
    :style="`background-color: ${campaign.color}`"
  >
    <article class="flex flex-col h-full" :class="`text-${textColor}`" role="article">
      <header>
        <div class="text-right">
          <span class="border border-current rounded-full py-1 px-2">
            {{ $t('duration') }}:
            {{ $dayjs(campaign.start).format( $t('dateFormat')) }}
            <template v-if="campaign.stop && campaign.stop !== campaign.start">
              – {{ $dayjs(campaign.stop).format( $t('dateFormat')) }}
            </template>
          </span>
        </div>
        <h2 v-if="campaign.title" class="my-4 text-xl lg:text-xxl">
          {{ campaign.title }}
        </h2>
      </header>
      <div v-if="campaign.shortDescription" class="break-word overflow-y-scroll scrollbar-hide campaign-description">
        <div v-html="campaignShortDescription" />
      </div>
      <footer v-if="campaign.partners.length > 0" class="mt-auto">
        <h3 class="font-semibold">{{ $t('campaign.partner') }}</h3>
        <div class="grid grid-cols-4 gap-4 mt-3">
          <a
            v-for="(partner, key) in campaign.partners"
            :key="key"
            :href="partner.url"
            target="_blank"
            class="flex flex-col flex-grow-1 justify-items-center my-auto"
          >
            <img
              v-if="partner.logo"
              :src="`${backendURL}/${partner.logo.contentUrl}`"
              :data-url="`${backendURL}/${partner.logo.contentUrl}`"
              :alt="partner.title"
              class="h-full w-full max-w-none w-auto max-h-25 max-w-48"
            />
            <p v-else>
              {{ partner.title }}
            </p>
          </a>
        </div>
      </footer>
    </article>
  </NuxtLink>
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

    const campaignShortDescriptionParagraphs = props.campaign.shortDescription.split(/(?:\r\n|\r|\n)/g);

    const campaignShortDescription = '<p>' + campaignShortDescriptionParagraphs.join('</p><p>') + '</p>'

    return {
      textColor,
      getContrastColorClass,
      backendURL: context.$config.backendURL,
      campaignShortDescription
    }
  },
})
</script>