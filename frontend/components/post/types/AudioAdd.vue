<template>
  <div>
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.audio.headline') }}
      </button>
    </div>
    <audio v-if="audioURL" :src="audioURL" controls></audio>
    <button @click.prevent="recordAudio">
      {{ isRecording ? 'Stop' : 'Start' }}
    </button>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  emits: ['abortPost'],
  setup(_, context) {
    let chunks: any[] = []
    let mediaRecorder: null | MediaRecorder
    let audioBlob = null

    const audioURL = ref('')
    const isRecording = ref(false)

    function recordAudio() {
      if (process.client) {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          alert('Browser does not support Audio Recording')
        } else if (!mediaRecorder) {
          navigator.mediaDevices.getUserMedia({ audio: true }).then((stream) => {
            mediaRecorder = new MediaRecorder(stream)
            mediaRecorder.start()
            isRecording.value = true
            mediaRecorder.ondataavailable = mediaRecorderDataAvailable
            mediaRecorder.onstop = mediaRecorderStop
          })
        } else {
          isRecording.value = false
          mediaRecorder.stop()
        }
      }
    }

    function mediaRecorderDataAvailable(e: { data: any }) {
      chunks.push(e.data)
    }

    function mediaRecorderStop(_e: any) {
      audioBlob = new Blob(chunks, { type: 'audio/mp3' })
      audioURL.value = window.URL.createObjectURL(audioBlob)
      mediaRecorder = null
      chunks = []
    }

    function abortPost() {
      context.emit('abortPost')
    }

    return {
      audioURL,
      isRecording,
      mediaRecorderDataAvailable,
      recordAudio,
      mediaRecorderStop,
      abortPost,
    }
  },
})
</script>
