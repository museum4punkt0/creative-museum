<template>
  <button type="button" class="text-2xl mb-6 back-btn" @click.prevent="backButton">
    <slot />
  </button>
</template>
<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const backButton = () => {
      history.back()
    }

    return {
      backButton,
    }
  },
})
</script>
