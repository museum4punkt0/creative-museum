<template>
  <div>
    <p w:text="lg" w:font="bold" w:mb="2" v-if="post.title">{{ post.title }}</p>
    <p>{{ post.body }}</p>
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
