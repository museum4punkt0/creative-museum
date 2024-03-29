<template>
  <div class="flex flex-row justify-between" :class="post.type === 'playlist' ? 'text-$highlight-contrast' : 'text-white'">
    <span class="flex flex-row items-center text-sm">
      <button
        v-tooltip="`${post.upvotes} ${$t('post.upvotes')}`"
        class="mr-2 -my-1 -ml-1 p-1 w-8 h-8 cursor-pointer transform-gpu rounded-md focus:outline-none focus-visible:bg-contrast"
        :aria-label="$t('post.voteDown')"
        :class="
          myVote === 'up' && post.type != 'playlist'
            ? 'text-$highlight focus-visible:text-$highlight-contrast'
            : 'fill-$highlight-contrast focus-visible:text-$highlight'
        "
        @click.prevent="doVotePost('up')"
        @keyup.enter.prevent="doVotePost('up')"
      >
        <LibraryIcon />
      </button>
      <span class="sr-only">$t('post.voting')</span> {{ votesTotal }}
      <button
        v-tooltip="`${post.downvotes} ${$t('post.downvotes')}`"
        class="ml-2 -my-1 p-1 w-8 h-8 transform-gpu rotate-180 cursor-pointer rounded-md focus:outline-none focus-visible:bg-contrast"
        :aria-label="$t('post.voteUp')"
        :class="
          myVote === 'down' && post.type != 'playlist'
            ? 'text-$highlight focus-visible:text-$highlight-contrast'
            : 'fill-$highlight-contrast focus-visible:text-$highlight'
        "
        @click.prevent="doVotePost('down')"
        @keyup.enter.prevent="doVotePost('down')"
      >
        <LibraryIcon
        />
      </button>
    </span>
    <button
      class="btn-outline text-sm ml-4 overflow-hidden overflow-ellipsis whitespace-nowrap"
      :class="
        post.type === 'playlist'
          ? 'text-$highlight-contrast border-$highlight-contrast hover:(bg-$highlight-contrast text-$highlight white border-contrast) focus-visible:(bg-contrast text-$highlight border-$highlight-contrast)'
          : post.rated
          ? 'text-$highlight border-$highlight focus-visible:(!text-white !border-white)'
          : ''
      "
      @click.prevent="triggerFeedback()"
    >
      {{ post.rated ? post.my_feedback.text : $t('post.feedback') }}
    </button>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  useContext,
  useStore,
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
  emits: ['triggerFeedback', 'voted'],
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
      } else if ($auth.loggedIn && props.post.campaign.closed) {
        store.dispatch('showCampaignClosed')
      } else  {
        const voteResponse = await votePost(props.post.id, direction)
        myVote.value = voteResponse.vote.direction
        votesTotal.value = voteResponse.votestotal
        context.emit('voted', props.post.id)
      }
    }

    function triggerFeedback() {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      } else {
        context.emit('triggerFeedback', props.post.id)
      }
    }

    return {
      myVote,
      votesTotal,
      doVotePost,
      triggerFeedback,
    }
  },
})
</script>
