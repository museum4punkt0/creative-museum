<template>
  <div v-if="comment" class="text-xs mt-4">
    <strong class="block">{{ comment.author.username }}</strong>
    <p>{{ comment.body }}</p>
    <span class="highlight-text block">{{
      $dayjs.duration($dayjs().diff($dayjs(comment.created))).days() > 2
        ? $dayjs(comment.created).format('DD.MM.YYYY')
        : $dayjs(comment.created).fromNow()
    }}</span>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    comment: {
      type: [Object, Array],
      default: () => {},
    },
  },
  setup() {},
})
</script>
