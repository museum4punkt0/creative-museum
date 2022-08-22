<template>
  <div>
    <div class="grid grid-cols-1 lg:grid-cols-12 lg:grid-gap-4">
      <div class="grid col-span-3 pr-10">
        <div v-if="isLargerThanLg">
          <UserCampaignInfo :campaign="campaign" />
        </div>
      </div>
      <div class="grid lg:col-span-6">
        <div v-if="campaign">
          <CampaignHead :campaign="campaign" />
          <div v-if="posts.value">
            <PostItem
              v-for="(post, key) in posts.value"
              :key="key"
              :post="post"
              :campaign-color="campaign.color"
              @updatePost="updatePost"
              @toggle-bookmark-state="toggleBookmarkState"
            />
          </div>
          <div v-else>No Posts</div>
        </div>
        <div v-else>No Campaign found</div>
      </div>
      <div class="grid col-span-3 pl-10">
        <div v-if="isLargerThanLg">
          <UserNotifications :campaign="campaign" />
          <UserAwards :campaign="campaign" class="mt-14 mb-10" />
        </div>
      </div>
    </div>
    <div v-if="!isLargerThanLg" class="xl:hidden">
      <PageFooter />
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  useAsync,
  useRoute,
  useRouter,
  computed,
  useContext,
  ref,
  useStore,
  watch,
} from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import { userApi } from '@/api/user'

export default defineComponent({
  name: 'CampaignPage',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const { $breakpoints, $auth } = useContext()
    const store = useStore()

    const postComment = ref(false)

    const newPost = computed(() => store.state.newPostOnCampaign)

    watch(newPost, (newValue) => {
      if (newValue === route.value.params.id) {
        loadCampaign()
        store.dispatch('resetNewPostOnCampaign')
      }
    })

    const { fetchCampaign } = campaignApi()
    const { fetchPost, fetchPostsByCampaign } = postApi()
    const { fetchUserInfoByCampaign } = userApi()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    let campaign = null
    const posts = ref(null)

    function loadCampaign() {
      if (route.value.params.id) {
        campaign = useAsync(
          () => fetchCampaign(route.value.params.id),
          `campaign-${route.value.params.id}`
        )
        posts.value = useAsync(
          () => fetchPostsByCampaign(route.value.params.id),
          `posts-${route.value.params.id}`
        )
        if (campaign.value && campaign.value.error) {
          router.push('/404')
        }
        if ($auth.loggedIn) {
          $auth.$storage.setState(
            'campaignScore',
            useAsync(
              () => fetchUserInfoByCampaign(route.value.params.id),
              `userinfo-${route.value.params.id}-${$auth.user.id}`
            )
          )
        }
      }
    }

    loadCampaign()

    function updatePost(postId) {
      posts.value.value.forEach(function (item, key) {
        if (item.id === postId) {
          fetchPost(postId).then(function (response) {
            posts.value.value[key].commentCount = response.commentCount
          })
        }
      })
    }

    function toggleBookmarkState(postId) {
      posts.value.value.forEach((item, key) => {
        if (item.id !== postId) {
          return
        }
        posts.value.value[key].bookmarked = !posts.value.value[key].bookmarked
      })
    }

    function hideCommentsFromOtherPosts(postId) {
      posts.value.value.forEach(function (item, key) {
        if (item.id === postId) {
          posts.value.value[key].showComments = true
        } else {
          posts.value.value[key].showComments = false
        }
      })
    }

    store.dispatch('showAddButton')
    store.dispatch('setCurrentCampaign', route.value.params.id)

    return {
      postComment,
      campaign,
      posts,
      newPost,
      isLargerThanLg,
      loadCampaign,
      updatePost,
      toggleBookmarkState,
      hideCommentsFromOtherPosts,
    }
  },
})
</script>
