<template>
  <div>
    <NuxtLink :to="`/playlist/${post.linkedPlaylist.id}`">{{ post.linkedPlaylist.title }}</NuxtLink>
  </div>
</template>
<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup() {},
})
</script>
