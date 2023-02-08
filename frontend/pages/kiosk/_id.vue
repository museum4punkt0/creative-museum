<template>
  <div>
    <div class="lg:grid lg:grid-cols-12 lg:gap-4">
      <div class="lg:col-span-3 pr-5">
        <qr-code v-if="campaign" :text="`${$config.baseURL}/campaigns/${campaign.id}`" />
      </div>
      <div class="lg:col-span-6">
        <div v-if="campaign">
          <CampaignHead v-if="campaign" :campaign="campaign" />
          <h2 class="sr-only">{{ $t('campaign.posts') }}</h2>
          <transition
            enter-active-class="duration-300 ease-out opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <button
              v-if="newPostsAvailable"
              class="btn-outline mx-auto px-6"
              @click.prevent="showNewPosts"
            >
              {{ $t('campaign.newPosts') }}
            </button>
          </transition>
          <CampaignResult v-if="campaignResult" :campaign-title="campaign.title" :campaign-result="campaignResult" :campaign-color="campaign.color" :campaign-closed="campaign.stop" />
          <PostList
            v-if="posts && posts.length"
            :posts="posts"
            source="campaign"
          />
          <UtilitiesLoadingIndicator
            v-else-if="!posts"
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
          />
          <div v-else>
            <button
              class="btn-highlight w-full mt-10"
              @click.prevent="showAddModal"
            >
              {{ $t('post.new') }}
            </button>
          </div>
        </div>
        <div v-else>
          <div class="container text-center min-h-2xl relative">
            <UtilitiesLoadingIndicator
              class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
            />
          </div>
        </div>
      </div>
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
  onUnmounted,
  useMeta,
} from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'

export default defineComponent({
  name: 'CampaignPage',
  layout: 'Kiosk',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const { i18n } = useContext()
    const store = useStore()
    const posts = ref(null)
    const newPosts = ref(null)
    const newPostsAvailable = ref(false)
    const campaign = ref(null)
    const campaignResult = ref(null)
    const { title } = useMeta()

    let refetchInterval = null

    const newPost = computed(() => store.state.newPostOnCampaign)

    const sortingKey = computed(
      () =>
      store.state.currentSorting +
      store.state.currentSortingDirection +
      store.state.filterId
      )

      watch(newPost, (newValue) => {
        if (newValue === route.value.params.id) {
          loadCampaign()
          store.dispatch('resetNewPostOnCampaign')
        }
      })

      watch(sortingKey, () => {
        loadCampaign()
      })

      const { fetchCampaign, fetchCampaignResult } = campaignApi()
      const { fetchPostsByCampaign } = postApi()

      async function loadCampaign() {
        if (route.value.params.id) {
        campaign.value = await fetchCampaign(route.value.params.id)
        posts.value = await fetchPostsByCampaign(
          route.value.params.id,
          store.state.currentSorting,
          store.state.currentSortingDirection,
          1
          )

          if (campaign.value && campaign.value.error) {
            router.push('/404')
          } else {
            store.dispatch('setCurrentCampaign', route.value.params.id)
            if (campaign.value.active && !campaign.value.closed) {
              store.dispatch('showAddButton')
            } else {
              store.dispatch('hideAddButton')
              campaignResult.value = await fetchCampaignResult(route.value.params.id)
            }
            title.value = campaign.value.title + ' | ' + i18n.t('pageTitle')
          }
        }
      }

      async function refetchPosts() {
        if (route.value.params.id) {
          newPosts.value = await fetchPostsByCampaign(
            route.value.params.id,
            store.state.currentSorting,
            store.state.currentSortingDirection,
            1
            )

            if (newPosts.value[0].id !== posts.value[0].id) {
              newPostsAvailable.value = true
            }
          }
        }

        function showNewPosts() {
          if (newPosts && newPosts.value.length) {
            posts.value = newPosts.value
            newPostsAvailable.value = false
          }
        }

        function showAddModal() {
          store.dispatch('showAddModal')
        }

        onMounted(async () => {
          await loadCampaign()

          refetchInterval = setInterval(() => {
            refetchPosts()
          }, 2500)
        })

        onUnmounted(function() {
          clearInterval(refetchInterval)
        })

        return {
          campaign,
          posts,
          campaignResult,
          newPost,
          newPostsAvailable,
          showAddModal,
          refetchPosts,
          showNewPosts
        }
      },
      head: {}
    })
  </script>
