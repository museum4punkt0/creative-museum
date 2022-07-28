<template>
  <div>
    <img
      v-if="post.files.length"
      :src="'https://backend.creative-museum.ddev.site/' + post.files[0].contentUrl"
      w:border="rounded"
      w:mx="auto" />
    <p w:my="3">
      {{post.body}}
    </p>
  </div>
</template>

<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  setup() {

  },
})
</script>
