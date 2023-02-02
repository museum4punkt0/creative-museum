<template>
  <div>
    <video
      v-if="post.files.length"
      class="w-full h-auto rounded-lg max-h-xl"
      controls
    >
      <source :src="`${backendURL}/${post.files[0].contentUrl}`" />
      <p v-if="post.files[0].description">{{ post.files[0].description }}</p>
    </video>

    <div class="my-3">
      <p v-if="post.title" class="text-lg font-bold mb-2">
        {{ post.title }}
      </p>
      <div class="break-words richtext" v-html="post.body" />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const context = useContext()

    return {
      backendURL: context.$config.backendURL,
    }
  },
})
</script>
