<template>
  <div v-if="comment" w:text="xs" w:m="t-4">
    <strong w:display="block">{{ comment.author.username }}</strong>
    <p>{{ comment.body }}</p>
    <span class="highlight-text" w:display="block">{{ $dayjs().isSameOrBefore(comment.created, 'day') ? $dayjs(comment.created).fromNow() :  $dayjs(comment.created).format('DD.MM.YYYY') }}</span>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    comment: {
      type: [Object, Array],
      default: () => {}
    }
  },
  setup() {

  },
})
</script>
