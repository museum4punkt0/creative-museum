<template>
  <div>
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.audio.headline') }}
      </button>
    </div>
    <VueRecordAudio mode="press" @result="onResult" />
    <audio v-if="audioURL" :src="audioURL" controls></audio>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  emits: ['abortPost'],
  setup(_, context) {

    const audioURL = ref('')

    function onResult(data : any) {
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
