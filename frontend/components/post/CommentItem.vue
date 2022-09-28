<template>
  <div v-if="comment" class="text-xs mt-4">
    <strong class="block">{{ $userName(comment.author) }}</strong>
    <p>{{ comment.body }}</p>
    <span
      class="block"
      :class="postType !== 'playlist' ? 'highlight-text' : ''"
      >{{
        $dayjs.duration($dayjs().diff($dayjs(comment.created))).days() > 2
          ? $dayjs(comment.created).format('DD.MM.YYYY')
          : $dayjs(comment.created).fromNow()
      }}</span
    >
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    postType: {
      type: String,
      default: '',
    },
    comment: {
      type: [Object, Array],
      default: () => {},
    },
  },
  setup() {},
})
</script>
