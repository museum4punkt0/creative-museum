<template>
  <div>
    <template v-if="post.title">Title: {{ post.title }}<br></template>
    Body: {{ post.body }}
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
