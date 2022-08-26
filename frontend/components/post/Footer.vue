<template>
  <div class="flex flex-row justify-between">
    <span class="flex flex-row items-center text-sm">
      <LibraryIcon
        class="mr-2 cursor-pointer"
        :class="myVote === 'up' && post.type != 'playlist' ? 'highlight-text' : 'fill-white'"
        @click.prevent="doVotePost('up')"
      />
      {{ votesTotal }}
      <LibraryIcon
        class="ml-2 transform-gpu rotate-180 cursor-pointer"
        :class="myVote === 'down' && post.type != 'playlist' ? 'highlight-text' : 'fill-white'"
        @click.prevent="doVotePost('down')"
      />
    </span>

    <button
      class="btn-outline text-sm"
      :class="post.type === 'playlist' ? `btn-text-${textColor}` : ''"
      @click.prevent="triggerFeedback()"
      type="button"
    >
      {{ $t('post.feedback') }}
    </button>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  useContext,
  useStore
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
  setup(props, context) {
    const store = useStore()
    const myVote = ref(null)
    const votesTotal = ref(null)
    const { $auth } = useContext()
    const { votePost } = postApi()

    onMounted(() => {
      votesTotal.value = props.post.votestotal
      if ($auth.loggedIn) {
        if (props.post.hasOwnProperty('my_vote')) {
          myVote.value = props.post.my_vote.direction
        }
      }
    })

    async function doVotePost(direction) {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      } else {
        const voteResponse = await votePost(props.post.id, direction)
        myVote.value = voteResponse.vote.direction
        votesTotal.value = voteResponse.votestotal
      }
    }

    function triggerFeedback() {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      } else {
        context.$emit('triggerFeedback', props.post.id)
      }
    }

    return {
      myVote,
      votesTotal,
      doVotePost,
      triggerFeedback
    }
  },
})
</script>
