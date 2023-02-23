<template>
  <div>
    <video
      v-show="post.files.length"
      class="w-full h-auto rounded-lg max-h-xl"
      controls
      :src="`${backendURL}${post.files[0].contentUrl}`"
    >
    </video>
    <p v-if="post.files[0].description">{{ post.files[0].description }}</p>
    <div class="my-3">
      <p v-if="post.title" class="text-lg font-bold mb-2">
        {{ post.title }}
      </p>
      <div class="break-words richtext" v-html="post.body" />
    </div>
  </div>
</template>
<script>

import { defineComponent, useContext } from '@nuxtjs/composition-api'
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
  setup() {
    const context = useContext()

    return {
      backendURL: context.$config.backendURL,
    }
  },
})
</script>
