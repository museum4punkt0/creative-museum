<template>
  <div
    w:display="block"
    w:border="rounded-lg"
    w:p="y-6 x-4"
    w:h="xl lg:3xl"
    w:shadow="md black/50"
    :style="`background-color: ${campaign.color}`"
  >

      <article w:flex="~ col" w:h="full" :w:text="textColor">
        <header>
          <div
            w:text="right"
          >
            <span
              w:border="~ current rounded-full"
              w:p="y-1 x-2"
            >
              {{ $dayjs(campaign.start).format('DD.MM.YYYY') }}
              <template v-if="campaign.end && campaign.end !== campaign.start">
              – {{ $dayjs(campaign.end).format('DD.MM.YYYY') }}
              </template>
            </span>
          </div>
          <h1
            v-if="campaign.title"
            w:m="y-4"
            w:text="xl lg:xxl"
          >
            <NuxtLink
              :to="`/campaigns/${campaign.id}`"
            >
              {{ campaign.title }}
            </NuxtLink>
          </h1>
        </header>
        <div v-if="campaign.shortDescription">
          {{ campaign.shortDescription }}
        </div>
        <footer w:m="t-auto">
          {{ $t('campaign.partner')}}
        </footer>
      </article>
  </div>
</template>
<script>
import { TinyColor, readability } from '@ctrl/tinycolor';
import { defineComponent } from '@nuxtjs/composition-api'
export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true
    }
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
      getContrastColorClass
    }
  },
})
</script>
