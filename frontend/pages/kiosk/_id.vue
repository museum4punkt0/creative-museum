<template>
  <div
    v-if="campaign"
    class="flex flex-col items-stretch flex-1 h-screen overflow-hidden"
  >
    <style type="text/css">
      body {
        --highlight: {{ campaign.color }};
        --highlight-contrast: {{ campaignContrastColor }};
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
            <qr-code
              v-if="campaign"
              :text="`${$config.baseURL}/campaigns/${campaign.id}`"
              class="w-full"
              :size="800"
            />
          </div>
          <KioskFilter :campaign="campaign" />
        </div>
      </div>
      <div class="relative col-span-9 h-full overflow-hidden pt-10 -mt-10 pl-6 kiosk-content">
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
              @initItems="initItems"
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
        <div class="box-shadow-inset rounded-xl ml-6 mr-6 relative">
          <div
            class="relative z-10 bg-$highlight rounded-xl h-5 text-center transition-all ease-linear duration-200 max-w-full"
            :style="`width: ${reset ? '0' : progress}%; transition-duration: ${reset ? '0' : timeout}ms`"
          />
          <span v-for="index in itemCount" :key="index+0" class="absolute rounded-full w-1 h-1 bg-white/30 block top-2 transform -translate-x-1" :class="index === 1 ? 'hidden' : ''" :style="`left: ${((index - 1) / itemCount) * 100}%`" />
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
  useMeta,
} from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  name: 'KioskPage',
  components: {
    Logo,
  },
  layout: 'Kiosk',
  setup() {
    const route = useRoute()
    const router = useRouter()
    const { i18n } = useContext()
    const store = useStore()
    const { title } = useMeta()

    const posts = ref(null)
    const campaign = ref(null)
    const campaignResult = ref(null)
    const step = ref(0)
    const reset = ref(false)
    const nextSorting = ref('votestotal')
    const progress = ref(0)
    const itemCount = ref(0)
    const timeout = 5000

    const newPost = computed(() => store.state.newPostOnCampaign)

    const campaignContrastColor = ref('#222329')

    function checkCampaignContrast() {
      const bgColor = new TinyColor(campaign.value.color)
      const fgColor = new TinyColor('#FFFFFF')
      const altfgColor = new TinyColor('#222329')

      const test1 = readability(bgColor, fgColor)
      const test2 = readability(bgColor, altfgColor)
      campaignContrastColor.value = (test1 < test2) ? '#222329' : '#FFFFFF'
    }

    watch(progress, (newProgress) => {
      if (Math.floor(newProgress) === 100) {
        setTimeout(() => {
          if (step.value === 0) {
            nextSorting.value = 'votestotal'
          }

          if (step.value === 1) {
            nextSorting.value = 'controversial'
          }

          if (step.value === 2) {
            nextSorting.value = 'date'
          }

          store.dispatch('setCurrentSortingWithDirection', [
            nextSorting.value,
            'desc',
          ])

          if (step.value < 2) {
            step.value++
          } else {
            step.value = 0
          }
          reset.value = true

          setTimeout(() => {
            reset.value = false
          }, 1)

        }, timeout)

      }

    })

    const sortingKey = computed(() => {
      return (
        store.state.currentSorting +
        store.state.currentSortingDirection +
        store.state.filterId
      )
    })

    watch(sortingKey, () => {
      loadCampaign()
    })

    const { fetchCampaign, fetchCampaignResult } = campaignApi()
    const { fetchPostsByCampaign } = postApi()

    async function loadCampaign() {
      progress.value = 0

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
          campaignResult.value = await fetchCampaignResult(
            route.value.params.id
          )
          checkCampaignContrast()
          title.value = campaign.value.title + ' | ' + i18n.t('pageTitle')
        }
      }
    }

    function initItems(emitValue) {
      itemCount.value = emitValue.itemCount
    }

    function updateProgress(emitValue) {
      progress.value = emitValue.progress
    }

    onMounted(async () => {
      await loadCampaign()
    })

    return {
      step,
      campaign,
      posts,
      campaignResult,
      newPost,
      progress,
      timeout,
      nextSorting,
      reset,
      campaignContrastColor,
      itemCount,
      updateProgress,
      initItems
    }
  },
  head: {},
})
</script>
<style scoped>
.kiosk-content {
  @apply after:content-[''] after:block after:absolute after:bottom-0 after:left-0 after:right-0 after:h-16 after:bg-gradient-to-t after:from-grey;
}
</style>