<template>
  <div>
    <div v-for="(video, key) in videos" :key="key" class="flex flex-col items-centerw-full mx-auto mt-10">
      <div
        class="relative h-0 overflow-hidden max-w-full w-full"
        style="padding-bottom: 56.25%"
      >
        <iframe
          :src="'https://www.youtube-nocookie.com/embed/' + video"
          frameborder="0"
          allowfullscreen
          class="absolute top-0 left-0 w-full h-full"
        ></iframe>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    video: {
      type: String,
      required: true,
    },
  },
  computed: {
    videos() {
      return this.video.split(',')
    },
  },
})
</script>
