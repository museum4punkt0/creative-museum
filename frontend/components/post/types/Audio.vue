<template>
  <div>
    <p v-if="post.title" class="text-lg font-bold mb-4">{{ post.title }}</p>
    <img
      v-if="image.length > 0"
      :src="image[0]"
      :data-url="image[0]"
      class="rounded mx-auto mb-6 max-h-xl"
      :alt="post.title ? post.title : ''"
    />
    <UtilitiesAudioPlayer :audio-list="[audio]" class="mt-4" />
  </div>
</template>
<script lang="ts">
import { defineComponent, computed, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const { $config } = useContext()

    const audio = computed(() => {
      return props.post.files
        .filter(function (item: any) {
          return item.type === 'audio'
        })
        .map(function (item: any) {
          return $config.backendUrl + item.contentUrl
        })
    })

    const image = computed(() => {
      return props.post.files
        .filter(function (item: any) {
          return item.type === 'image'
        })
        .map(function (item: any) {
          return $config.backendUrl + item.contentUrl
        })
    })

    return {
      audio,
      image,
    }
  },
})
</script>
