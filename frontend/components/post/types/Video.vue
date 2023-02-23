<template>
  <div>
    <div
      class="rounded-lg max-h-xl overflow-hidden"
      @click="playVideo('video-' + post.id)"
    >
      <client-only>
        <VueVideoThumbnail
          v-if="!showVideo"
          :video-src="`${backendURL}/${post.files[0].contentUrl}`"
          show-play-button
          :width="688"
          :height="388"
          class="!w-full"
          :cors="true"
        />
      </client-only>
    </div>
    <video
      v-show="post.files.length && showVideo"
      :id="`video-${post.id}`"
      class="w-full h-auto rounded-lg max-h-xl"
      controls
    >
      <source :src="`${backendURL}/${post.files[0].contentUrl}`" />
      <p v-if="post.files[0].description">{{ post.files[0].description }}</p>
    </video>
    <div class="my-3">
      <p v-if="post.title" class="text-lg font-bold mb-2">
        {{ post.title }}
      </p>
      <div class="break-words richtext" v-html="post.body" />
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, useContext, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
    displayMode: {
      type: String,
      default: 'Web'
    }
  },
  setup() {
    const context = useContext()
    const showVideo = ref(false)
    const video = ref(null)

    function playVideo(videoId: string) {
      showVideo.value = true
      const videoPlayer = <HTMLVideoElement> document.getElementById(videoId)
      if (process.client) {
        videoPlayer.play()
      }
    }

    return {
      video,
      showVideo,
      backendURL: context.$config.backendURL,
      playVideo
    }
  },
})
</script>
