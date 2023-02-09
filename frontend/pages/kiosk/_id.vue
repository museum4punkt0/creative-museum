<template>
  <div v-if="campaign" class="flex flex-col items-stretch flex-1 h-screen overflow-hidden">
    <style type="text/css">
      body {
        --highlight: {{ campaign.color }};
      }
    </style>
    <div class="p-10 grid grid-cols-12 w-full">
      <div class="col-span-3">
        <div class="text-$highlight">
          <Logo
            class="h-15 mb-10 w-auto ml-5 transform-gpu transition-all duration-300 ease-in-out cursor-pointer"
            aria-label="Creative Museum Logo"
          />
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
        </div>
      </div>
    </div>
    <div class="grid grid-cols-12 w-full h-full flex flex-grow-1">
      <div class="col-span-3">
        <div class="pl-10 pr-16">
          <h2 class="text-white text-xl mb-3">{{ $t('kiosk.qrHeadline') }}</h2>
          <div class="box-shadow-inset w-full p-4 rounded-lg mb-10">
            <qr-code v-if="campaign" :text="`${$config.baseURL}/campaigns/${campaign.id}`" class="w-full" :size="400" />
          </div>
          <KioskFilter :campaign="campaign" />
        </div>
      </div>
      <div class="col-span-9 h-full overflow-scroll pt-10 -mt-10 pl-6">
        <div v-if="campaign">
          <div class="relative mr-6">
            <KioskPostList
              v-if="posts && posts.length"
              :posts="[campaignResult, posts]"
              :campaign="campaign"
              source="campaign"
              :timeout="timeout"
              :progress="progress"
              @updateProgress="updateProgress"
            />
            <UtilitiesLoadingIndicator
              v-else-if="!posts"
              class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
            />
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
    <div class="grid grid-cols-12 py-10 bg-grey">
      <div class="col-span-9 col-start-4">
        <div class="box-shadow-inset rounded-xl ml-6 mr-6">
          <div
            class="bg-$highlight rounded-xl h-5 text-$highlight-contrast text-center transition-all ease-in-out duration-200"
            :style="`width: ${progress}%`"
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
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  name: 'KioskPage',
  components: {
    Logo
  },
  layout: 'Kiosk',
  setup(_, context) {
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
    const step = ref(0)
    const nextSorting = ref('votestotal')

    const timeout = 10000

    const progress = ref(0)

    let refetchInterval = null

    const newPost = computed(() => store.state.newPostOnCampaign)

    watch(progress, (newProgress) => {

      if (newProgress === 100) {

        if (step.value === 1) {
          nextSorting.value = 'controversial'
        }

        setTimeout(() => {
          store.dispatch('setCurrentSortingWithDirection', [
            nextSorting.value,
            'desc',
          ])
          progress.value = 0
        }, timeout)


        if (step.value === 2) {
          step.value = 0
          nextSorting.value = 'date'
        } else {
          step.value++
        }

      }
    });

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

        function updateProgress(emitValue) {
          console.log(emitValue)
          progress.value = emitValue.progress
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
          progress,
          timeout,
          nextSorting,
          showAddModal,
          refetchPosts,
          showNewPosts,
          updateProgress
        }
      },
      head: {}
    })
  </script>
