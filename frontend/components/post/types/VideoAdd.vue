<template>
  <div>
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">
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
