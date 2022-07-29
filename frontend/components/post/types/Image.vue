<template>
  <div>
    <img
      v-if="post.files.length"
      :src="'https://backend.creative-museum.ddev.site/' + post.files[0].contentUrl"
      w:border="rounded"
      w:mx="auto"
      :alt="post.files[0].description ? post.files[0].description : (post.title ? post.title : post.body) " />
    <p w:my="3">
      <template v-if="post.title">Title: {{ post.title }}<br></template>
      {{ post.body }}
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
