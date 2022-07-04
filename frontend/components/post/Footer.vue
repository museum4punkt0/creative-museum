<template>
  <div w:flex="~ row" w:justify="between">
    <span w:flex="~ row" w:align="items-center" w:text="sm">
      <LibraryIcon w:m="r-2" />
      {{ post.votestotal }}
      <LibraryIcon  w:m="l-2" w:transform="gpu rotate-180" />
    </span>
    <button class="btn-outline" w:text="sm">Feedback</button>

  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'
import LibraryIcon from '@/assets/icons/library.svg?inline'

export default defineComponent({
  components: {
    LibraryIcon
  },
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
