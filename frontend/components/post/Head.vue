<template>
  <div
    v-if="post.author"
    w:flex="~ row"
    w:justify="between"
  >
    <NuxtLink
      :to="localePath(`/user/${post.author.username}`)"
      w:flex="~ row"
    >
      <UserProfileImage :user="post.author" w:m="r-4" />
      <div w:flex="~ col">
        <span w:text="lg">{{ post.author.username }}</span>
        <span class="highlight-text" w:text="sm" w:m="t-1">{{ $dayjs.duration($dayjs().diff($dayjs(post.created))).days() > 2 ? $dayjs(post.created).format('DD.MM.YYYY') : $dayjs(post.created).fromNow() }}</span>
      </div>
    </NuxtLink>
    <div>
      <ThreeDots />
    </div>
  </div>
</template>
<script>
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
