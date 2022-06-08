<template>
  <div>
    <NuxtLink :w:text="textColor" :to="`/campaigns/${campaign.id}`">
      {{ campaign.title }}
    </NuxtLink>
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
