<template>
  <div class="relative pb-10">
    <KioskPostItem
      v-for="post in posts"
      :key="post.id"
      :post="post"
    />
  </div>
</template>
<script>
import {
  defineComponent
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    posts: {
      type: Array,
      required: true,
    },
    source: {
      type: String,
      default: '',
    },
  }
})
</script>
