<template>
  <div w:flex="~ row" w:justify="between">
    <span w:flex="~ row" w:align="items-center" w:text="sm">
      <LibraryIcon
        :class="
          (yourVote &&
            yourVote.value &&
            yourVote.value.direction &&
            yourVote.value.direction === 'up') ||
          (yourVote &&
            yourVote.value &&
            yourVote.value.vote &&
            yourVote.value.vote.direction &&
            yourVote.value.vote.direction === 'up')
            ? 'highlight-text'
            : 'fill-white'
        "
        w:m="r-2"
        w:cursor="pointer"
        @click.prevent="doVotePost('up')"
      />
      {{
        yourVote && yourVote.value && Math.abs(yourVote.value.votestotal) >= 0
          ? yourVote.value.votestotal
          : post.votestotal
      }}
      <LibraryIcon
        :class="
          (yourVote &&
            yourVote.value &&
            yourVote.value.direction &&
            yourVote.value.direction === 'down') ||
          (yourVote &&
            yourVote.value &&
            yourVote.value.vote &&
            yourVote.value.vote.direction &&
            yourVote.value.vote.direction === 'down')
            ? 'highlight-text'
            : 'fill-white'
        "
        w:m="l-2"
        w:transform="gpu rotate-180"
        w:cursor="pointer"
        @click.prevent="doVotePost('down')"
      />
    </span>

    <button
      class="btn-outline"
      w:text="sm"
      :class="post.type === 'playlist' ? `btn-text-${textColor}` : ''"
      @click.prevent="$emit('triggerFeedback', post.id)"
    >
      {{ $t('post.feedback') }}
    </button>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  useAsync,
  useContext,
} from '@nuxtjs/composition-api'
import LibraryIcon from '@/assets/icons/library.svg?inline'
import { postApi } from '@/api/post'

export default defineComponent({
  components: {
    LibraryIcon,
  },
  props: {
    post: {
      type: Object,
      required: true,
    },
    textColor: {
      type: String,
      required: true,
    },
  },
  emits: ['triggerFeedback'],
  setup(props) {
    const yourVote = ref(null)
    const voteCount = ref(null)
    const { $auth } = useContext()
    const { votePost, fetchYourVoteByPost } = postApi()

    if ($auth.loggedIn) {
      yourVote.value = useAsync(
        () => fetchYourVoteByPost(props.post.id),
        `post_yourvote_${props.post.id}`
      )
    }

    function doVotePost(direction) {
      yourVote.value = useAsync(
        () => votePost(props.post.id, direction),
        `post_yourvote_${props.post.id}`
      )
    }

    return {
      yourVote,
      voteCount,
      doVotePost,
    }
  },
})
</script>
