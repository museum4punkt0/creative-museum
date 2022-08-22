<template>
  <div>
    <p v-if="post.title" class="text-lg font-bold mb-2">{{ post.title }}</p>
    <p>{{ post.body }}</p>
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
