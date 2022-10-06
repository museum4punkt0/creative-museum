<template>
  <NuxtLink
    :to="localePath('/campaigns/' + campaign.id)"
    class="shadow-md shadow-black/50 h-xl lg:h-3xl py-6 px-4 rounded-lg block lg:pointer-events-none"
    :style="`background-color: ${campaign.color}`"
  >
    <article class="flex flex-col h-full" :class="`text-${textColor}`">
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
        <h1 v-if="campaign.title" class="my-4 text-xl lg:text-xxl">
          {{ campaign.title }}
        </h1>
      </header>
      <div v-if="campaign.shortDescription" class="break-word overflow-y-scroll scrollbar-hide">
        {{ campaign.shortDescription }}
      </div>
      <footer v-if="campaign.partners.length > 0" class="mt-auto">
        <h3 class="font-semibold">{{ $t('campaign.partner') }}</h3>
        <div class="flex flex-row flex-wrap gap-4">
          <a
            v-for="(partner, key) in campaign.partners"
            :key="key"
            :href="partner.url"
            target="_blank"
            class="mt-3 flex flex-col flex-grow-1 justify-items-center"
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

    return {
      textColor,
      getContrastColorClass,
      backendURL: context.$config.backendURL,
    }
  },
})
</script>
