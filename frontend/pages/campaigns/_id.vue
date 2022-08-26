<template>
  <div>
    <div class="lg:grid lg:grid-cols-12 lg:gap-4">
      <div class="lg:col-span-3 pr-5">
        <div v-if="isLargerThanLg">
          <UserCampaignInfo v-if="campaign" :campaign="campaign" />
        </div>
      </div>
      <div class="lg:col-span-6">
        <div v-if="campaign">
          <CampaignHead v-if="campaign" :campaign="campaign" />
          <div v-if="posts && posts.length">
            <PostItem
              v-for="(post) in posts"
              :key="post.id"
              :post="post"
              :campaign-color="campaign.color"
              @updatePost="updatePost"
              @toggle-bookmark-state="toggleBookmarkState"
            />
          </div>
          <div v-else><button class="btn-highlight w-full mt-10" @click.prevent="showAddModal">{{ $t('post.new') }}</button></div>
        </div>
        <div v-else>
          <div class="container text-center min-h-2xl relative">
            <UtilitiesLoadingIndicator class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" />
          </div>
        </div>
      </div>
      <div class="lg:col-span-3 pl-5">
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
  useRoute,
  useRouter,
  computed,
  useContext,
  ref,
  useStore,
  watch,
  onMounted,
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
    const posts = ref(null)
    const postComment = ref(false)
    const campaign = ref(null)

    const newPost = computed(() => store.state.newPostOnCampaign)

    const sortingKey = computed(() => (store.state.currentSorting + store.state.currentSortingDirection))

    watch(newPost, (newValue) => {
      if (newValue === route.value.params.id) {
        loadCampaign()
        store.dispatch('resetNewPostOnCampaign')
      }
    })

    watch (sortingKey, () => {
      loadCampaign()
    })

    const { fetchCampaign } = campaignApi()
    const { fetchPost, fetchPostsByCampaign } = postApi()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    async function loadCampaign() {
      if (route.value.params.id) {
        campaign.value = await fetchCampaign(route.value.params.id)
        posts.value = await fetchPostsByCampaign(
          route.value.params.id,
          store.state.currentSorting,
          store.state.currentSortingDirection
        )

        if (campaign.value && campaign.value.error) {
          router.push('/404')
        }
      }
    }

    function showAddModal() {
      store.dispatch('showAddModal')
    }

    onMounted(async () => {
      await loadCampaign()
    })

    function updatePost(postId) {
      posts.value.forEach(function (item, key) {
        if (item.id === postId) {
          fetchPost(postId).then(function (response) {
            posts.value[key].commentCount = response.commentCount
            posts.value[key].upvotes = response.upvotes
            posts.value[key].downvotes = response.downvotes
          })
        }
      })
    }

    function toggleBookmarkState(postId) {
      posts.value.forEach((item, key) => {
        if (item.id !== postId) {
          return
        }
        posts.value[key].bookmarked = !posts.value[key].bookmarked
      })
    }

    function hideCommentsFromOtherPosts(postId) {
      posts.value.forEach(function (item, key) {
        if (item.id === postId) {
          posts.value[key].showComments = true
        } else {
          posts.value[key].showComments = false
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
      showAddModal
    }
  },
})
</script>
