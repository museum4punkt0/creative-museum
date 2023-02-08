<template>
  <div>
    <KioskPostItem
      v-for="post in posts"
      :key="post.id"
      :post="post"
      class="absolute l-0 t-0 r-0 b-0 w-full bg-grey"
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
