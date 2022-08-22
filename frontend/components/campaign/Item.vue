<template>
  <div
    class="shadow-md shadow-black/50 h-xl lg:h-3xl py-6 px-4 border rounded-lg block"
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
      <footer class="mt-auto">
        {{ $t('campaign.partner') }}
      </footer>
    </article>
  </div>
</template>
<script>
import { TinyColor, readability } from '@ctrl/tinycolor'
import { defineComponent } from '@nuxtjs/composition-api'
export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const textColor = getContrastColorClass()

    function getContrastColorClass() {
      const bgColor = new TinyColor(props.campaign.color)
      const fgColor = new TinyColor('#FFFFFF')
      return readability(bgColor, fgColor) > 2 ? 'white' : 'black'
    }

    return {
      textColor,
      getContrastColorClass,
    }
  },
})
</script>
