<template>
  <div>
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.audio.headline') }}
      </button>
    </div>
    <div class="box-shadow">
      <client-only>


          <UtilitiesAudioPlayer
            v-if="audioURL"
            ref="audioPlayer"
            :audio-list="[audioURL]"
            :show-prev-button="false"
            :show-next-button="false"
            :show-volume-button="false"
            :show-playback-rate="false"
            :progress-interval="10"
            :is-loop="false"
            theme-color="#FFFF00"
          />
      </client-only>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  emits: ['abortPost'],
  setup(_, context) {

    const audioURL = ref('')

    function onResult(data: any) {
      audioURL.value = window.URL.createObjectURL(data)
    }

    function abortPost() {
      context.emit('abortPost')
    }

    return {
      audioURL,
      onResult,
      abortPost,
    }
  },
})
</script>
