<template>
  <div>
    <p v-if="post.title" class="text-lg font-bold mb-2">{{ post.title }}</p>
    <UtilitiesAudioPlayer :audio-list="[audio]" />
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
        return props.post.files.map(function(item : any) {
            return $config.backendUrl + item.contentUrl;
        })
      })

      return {
        audio
      }
    },
})
</script>
