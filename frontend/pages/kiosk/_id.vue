<template>
  <div v-if="campaign"  class="p-10 grid grid-cols-12 w-full">
    <style type="text/css">
      body {
        --highlight: {{ campaign.color }};
      }
    </style>
    <div class="col-span-3">
      <div class="text-$highlight">
        <Logo
          class="h-15 mb-10 w-auto ml-5 transform-gpu transition-all duration-300 ease-in-out cursor-pointer"
          aria-label="Creative Museum Logo"
        />
        <div class="box-shadow-inset inline-block p-4 rounded-lg">
          <qr-code v-if="campaign" :text="`${$config.baseURL}/campaigns/${campaign.id}`" />
        </div>
      </div>
    </div>
    <div class="col-span-9">
      <div v-if="campaign">

        <div class="mb-6">
          <h1 class="page-header lg:mt-0 mb-1">{{ campaign.title }}</h1>
          <p class="text-lg">
            <span class="capitalize">{{ $t('till') }}</span>
            {{ $dayjs(campaign.stop).format($t('dateFormat')) }}
          </p>
        </div>

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
import { TinyColor, readability } from '@ctrl/tinycolor'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  name: 'KioskPage',
  components: {
    Logo
  },
  layout: 'Kiosk',
  setup(props) {
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
            store.dispatch('hideAddButton')
            campaignResult.value = await fetchCampaignResult(route.value.params.id)
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
