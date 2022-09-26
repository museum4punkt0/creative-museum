<template>
  <svg :alt="title" version="1.1" class="w-full" viewBox="0 0 137 180" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <path d="M 2.9752066,21.663342 68.108099,5.7378985 133.24109,21.663342 v 88.659738 c 0,35.4163 -29.29771,63.93902 -65.37513,63.93902 -35.593046,0 -64.8907534,-28.52272 -64.8907534,-63.93902 z" fill="#fff" />
    <mask id="maskBadge">
      <path transform="translate(2.5 2.5) scale(0.97)" d="M 2.9752066,21.663342 68.108099,5.7378985 133.24109,21.663342 v 88.659738 c 0,35.4163 -29.29771,63.93902 -65.37513,63.93902 -35.593046,0 -64.8907534,-28.52272 -64.8907534,-63.93902 z" fill="#fff" />
    </mask>
    <defs>
      <pattern
        width="1"
        height="1">
        <use
          transform="translate(0.0602678) scale(0.00446429 0.00337838)" />
      </pattern>
    </defs>
  <image class="w-full h-auto" xmlns:xlink="http://www.w3.org/1999/xlink" :href="`${backendURL}/${image.contentUrl}`" mask="url(#maskBadge)" />
  </svg>
</template>
<script>
import { defineComponent, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    image: {
      type: Object,
      required: true
    },
    title: {
      type: String,
      default: ''
    }
  },
  setup() {
    const { $config } = useContext()
    return {
      backendURL: $config.backendURL,
    }
  }
})
</script>
<style scoped>
  svg path {
    stroke: var(--highlight);
    stroke-width: 2px;
    stroke-linejoin: round;
  }
</style>
