<template>
  <div>
    <img
      v-if="post.files.length"
      :src="`${backendURL}/${post.files[0].contentUrl}`"
      :data-url="`${backendURL}/${post.files[0].contentUrl}`"
      class="rounded mx-auto max-h-xl"
      :alt="
        post.files[0].description
          ? post.files[0].description
          : ''
      "
    />
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
