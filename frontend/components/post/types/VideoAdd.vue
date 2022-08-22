<template>
  <div>
    <div class="page-header p-6">
      <button class="back-btn" @click.prevent="abortPost" type="button">
        {{ $t('post.types.video.headline') }}
      </button>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  emits: ['abortPost'],
  setup(_, context) {
    function abortPost() {
      context.emit('abortPost')
    }
    return {
      abortPost,
    }
  },
})
</script>
