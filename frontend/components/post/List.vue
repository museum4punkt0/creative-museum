<template>
  <div class="relative pb-10">
    <PostItem
      v-for="post in postsRef"
      :key="post.id"
      :post="post"
      @updatePost="updatePost"
      @toggle-bookmark-state="toggleBookmarkState"
      @postDeleted="deletePost(post.id)"
    />
    <InfiniteLoading @infinite="infiniteHandler">
      <div slot="spinner">
        <UtilitiesLoadingIndicator
          class="absolute left-1/2 transform -translate-x-1/2 bottom-0"
          :small="true"
        />
      </div>
      <div slot="no-more" class="mt-4 text-sm text-white/50">
        {{ $t('campaign.noMorePosts') }}
      </div>
      <div slot="no-results"></div>
    </InfiniteLoading>
  </div>
</template>
<script>
import InfiniteLoading from 'vue-infinite-loading'
import {
  defineComponent,
  useStore,
  ref,
  toRef,
  useContext,
  useRoute,
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  components: {
    InfiniteLoading,
  },
  props: {
    posts: {
      type: Array,
      required: true,
    },
    source: {
      type: String,
      default: '',
    },
  },
  setup(props) {
    const store = useStore()
    const route = useRoute()
    const { $config, $auth } = useContext()
    const { fetchPost, fetchUserPosts, fetchPostsByCampaign } = postApi()

    const postsRef = toRef(props, 'posts')

    const currentPage = ref(1)

    function updatePost(postId) {
      postsRef.value.forEach(function (item, key) {
        if (item.id === postId) {
          fetchPost(postId).then(function (response) {
            postsRef.value[key].commentCount = response.commentCount
            postsRef.value[key].upvotes = response.upvotes
            postsRef.value[key].downvotes = response.downvotes
            if (
              response.userChoiced &&
              response.pollOptions &&
              response.choicesTotal
            ) {
              postsRef.value[key].choicesTotal = response.choicesTotal
              postsRef.value[key].userChoiced = response.userChoiced
              postsRef.value[key].pollOptions = response.pollOptions
            }
            if (response.rated && response.my_feedback) {
              postsRef.value[key].rated = response.rated
              postsRef.value[key].my_feedback = response.my_feedback
            }
          })
        }
      })
    }

    function toggleBookmarkState(postId) {
      postsRef.value.forEach((item, key) => {
        if (item.id !== postId) {
          return
        }
        postsRef.value[key].bookmarked = !postsRef.value[key].bookmarked
      })
    }

    function hideCommentsFromOtherPosts(postId) {
      postsRef.value.forEach(function (item, key) {
        if (item.id === postId) {
          postsRef.value[key].showComments = true
        } else {
          postsRef.value[key].showComments = false
        }
      })
    }

    async function infiniteHandler($state) {
      if (postsRef.value.length >= $config.postsPerPage) {
        currentPage.value += 1
        await getPosts().then((response) => {
          if (response.length) {
            postsRef.value.push(...response)
            $state.loaded()
          } else {
            $state.complete()
          }
        })
      } else {
        $state.complete()
      }
    }

    function getPosts() {
      if (props.source === 'campaign') {
        return fetchPostsByCampaign(
          route.value.params.id,
          store.state.currentSorting,
          store.state.currentSortingDirection,
          currentPage.value
        )
      }
      if (props.source === 'userprofile') {
        return fetchUserPosts($auth.user.id, currentPage.value)
      }
      if (props.source === 'playlist') {
        fetchPlaylist(props.playlist, currentPage.value)
      }
    }

    function deletePost(postId) {
      postsRef.value.forEach(function (item, key) {
        if (item.id === postId) {
          postsRef.value.splice(key, 1)
        }
      })
    }

    return {
      currentPage,
      postsRef,
      updatePost,
      toggleBookmarkState,
      hideCommentsFromOtherPosts,
      infiniteHandler,
      deletePost,
    }
  },
})
</script>
