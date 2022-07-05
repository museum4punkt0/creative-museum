<template>
  <div w:flex="~ row" w:justify="between">
    <span w:flex="~ row" w:align="items-center" w:text="sm">
      <LibraryIcon :class="yourVote.value && yourVote.value.direction && yourVote.value.direction === 'up' ? 'highlight-text' : 'fill-white'" w:m="r-2" @click.prevent="doVotePost('up')" />
      {{ yourVote.value && yourVote.value.post.votestotal ? yourVote.value.post.votestotal : post.votestotal }}
      <LibraryIcon :class="yourVote.value && yourVote.value.direction && yourVote.value.direction === 'down' ? 'highlight-text' : 'fill-white'" w:m="l-2" w:transform="gpu rotate-180" @click.prevent="doVotePost('down')" />
    </span>
    <button class="btn-outline" w:text="sm">Feedback</button>
  </div>
</template>
<script>
import { defineComponent, ref, useAsync, watch } from '@nuxtjs/composition-api'
import LibraryIcon from '@/assets/icons/library.svg?inline'
import { postApi } from '@/api/post'

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
  setup(props) {

    const yourVote = ref(null)
    const voteCount = ref(null)
    const { votePost, fetchYourVoteByPost } = postApi()

    yourVote.value = useAsync(() => fetchYourVoteByPost(props.post.id), `posts_yourvote_${props.post.id}`)

    function doVotePost(direction) {
      yourVote.value = useAsync(() => votePost(props.post.id, direction), `post_yourvote_${props.post.id}`)
    }

    return {
      yourVote,
      voteCount,
      doVotePost
    }
  },
})
</script>
