<template>
  <div
    v-if="
      (availableAwards.length || unavailableAwards.length || receivedAwards.length) &&
      (!campaign || campaign.active)
    "
    class="mb-12"
  >
    <div class="flex flex-row justify-between mb-10">
      <span class="text-2xl">{{ $t('campaign.awards') }}</span>
    </div>
    <div v-if="availableAwards.length" class="mb-6">
      <div class="text-$highlight text-sm mb-2">
        {{ $t('awards.available') }}
      </div>
      <AwardItem
        v-for="(award, key) in availableAwards"
        :key="key"
        :award="award"
        @awardsChange="fetchAllAwards"
      />
    </div>
    <div v-if="unavailableAwards.length" class="mb-6">
      <div class="text-$highlight text-sm mb-2">
        {{
          $auth.loggedIn
            ? $t('awards.unavailable')
            : $t('awards.loginToReceiveAwards')
        }}
      </div>
      <AwardItem
        v-for="(award, key) in unavailableAwards"
        :key="key"
        :award="award"
      />
    </div>
    <div v-if="giftedAwards.length" class="mb-6">
      <div class="flex flex-row justify-between">
        <h3 class="text-$highlight text-sm mb-2">{{ $t('awards.gifted') }}</h3>
        <button
          v-if="
            (!campaign && giftedAwards.length > 3) ||
            (campaign && giftedAwards.length > 3)
          "
          class="highlight-text text-sm flex flex-row items-center leading-none cursor-pointer whitespace-nowrap"
          @click.prevent="showMoreGifted = !showMoreGifted"
        >
          <ArrowIcon
            class="relative w-2 top-0 mr-0.5 inline-block transition-all duration-200"
            :class="showMoreGifted ? 'transform-gpu rotate-180' : ''"
          />
          <span v-if="!showMoreGifted">{{ $t('showAll') }}</span>
          <span v-else>{{ $t('hide') }}</span>
        </button>
      </div>
      <div v-for="(award, key) in giftedAwards" :key="key">
        <div v-if="key < 3 || showMoreGifted">
          <AwardItem
            :award="award"
          />
        </div>
      </div>
    </div>
    <div v-if="receivedAwards.length" class="mb-6">
      <div class="flex flex-row justify-between">
        <h3 class="text-$highlight text-sm mb-2">{{ $t('awards.received') }}</h3>
        <button
          v-if="
            (!campaign && receivedAwards.length > 3) ||
            (campaign && receivedAwards.length > 3)
          "
          class="highlight-text text-sm flex flex-row items-center leading-none cursor-pointer"
          @click.prevent="showMoreReceived = !showMoreReceived"
        >
          <ArrowIcon
            class="relative w-2 top-0 mr-0.5 inline-block transition-all duration-200"
            :class="showMoreReceived ? 'transform-gpu rotate-180' : ''"
          />
          <span v-if="!showMoreReceived">{{ $t('showAll') }}</span>
          <span v-else>{{ $t('hide') }}</span>
        </button>
      </div>
      <div v-for="(award, key) in receivedAwards" :key="key">
        <div v-if="key < 3 || showMoreReceived">
          <AwardItem
            :award="award.award"
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  useContext,
  useStore,
  watch,
  onMounted,
  ref,
} from '@nuxtjs/composition-api'
import { awardApi } from '@/api/award'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'

export default defineComponent({
  components: {
    ArrowIcon
  },
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const store = useStore()
    const { fetchAwards, fetchAwarded } = awardApi()
    const { $auth } = useContext()

    const availableAwards = ref([])
    const unavailableAwards = ref([])
    const giftedAwards = ref([])
    const receivedAwards = ref([])
    const showMoreGifted = ref(false)
    const showMoreReceived = ref(false)

    onMounted(async () => {
      await fetchAllAwards()
    })

    watch(
      () => store.getters.awardsChange,
      function () {
        fetchAllAwards()
      }
    )

    async function fetchAllAwards() {
      availableAwards.value = []
      unavailableAwards.value = []
      giftedAwards.value = []
      receivedAwards.value = []

      await fetchAwards(props.campaign ? props.campaign.id : null).then(
        function (response) {
          if ($auth.loggedIn) {
            response.forEach((item) => {
              if (item.available && !item.taken) {
                availableAwards.value.push(item)
              } else if (item.taken) {
                giftedAwards.value.push(item)
              } else if (unavailableAwards.length === 0) {
                unavailableAwards.value.push(item)
              }
            })
          } else {
            unavailableAwards.value = response
          }
          store.dispatch('awardsChanged')
        }
      )
      if ($auth.loggedIn) {
        receivedAwards.value = await fetchAwarded()
      }
    }

    return {
      availableAwards,
      unavailableAwards,
      giftedAwards,
      receivedAwards,
      showMoreGifted,
      showMoreReceived,
      fetchAllAwards,
    }
  },
})
</script>
