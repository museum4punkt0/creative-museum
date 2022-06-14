<template>
  <div
    w:border="rounded-lg"
    w:p="y-6 x-4"
    w:h="xl lg:3xl"
    w:shadow="md black/50"
    :style="`background-color: ${campaign.color}`"
  >
    <article :w:text="textColor">
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
        <h1 w:text="xl lg:xxl" >
          {{ campaign.title }}
        </h1>
      </header>
      <NuxtLink :w:text="textColor" :to="`/campaigns/${campaign.id}`"></NuxtLink>
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
