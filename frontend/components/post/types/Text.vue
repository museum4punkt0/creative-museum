<template>
  <div>
    <p v-if="post.title" class="text-lg font-bold mb-2">{{ post.title }}</p>
    <div class="break-words richtext" v-html="post.body" />
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
    displayMode: {
      type: String,
      default: 'Web'
    }
  },
  setup() {},
})
</script>
