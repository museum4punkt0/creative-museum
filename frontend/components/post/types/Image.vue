<template>
  <div>
    <img
      v-if="post.files.length"
      :src=`${backendUrl}/${post.files[0].contentUrl}`
      class="rounded mx-auto"
      :alt="
        post.files[0].description
          ? post.files[0].description
          : post.title
          ? post.title
          : post.body
      "
    />
    <div class="my-3">
      <p v-if="post.title" class="text-lg font-bold mb-2">
        {{ post.title }}
      </p>
      <p>{{ post.body }}</p>
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
      backendUrl: context.$config.backendUrl
    }

  },
})
</script>
