<template>
  <div
    v-if="
      (availableAwards.length || unavailableAwards.length || receivedAwards.length || giftedAwards.length) &&
      (!campaign || campaign.active)
    "
    class="mb-12"
  >
    <div class="flex flex-row justify-between mb-10">
      <h2 class="text-2xl">{{ $t('campaign.awards') }}</h2>
    </div>
    <div v-if="availableAwards.length" class="mb-6">
      <h2 class="text-$highlight text-sm mb-2">
        {{ $t('awards.available') }}
      </h2>
      <ul>
        <li v-for="(award, key) in availableAwards" :key="key">
          <AwardItem
            :key="key"
            :award="award"
            :available="true"
            @awardsChange="fetchAllAwards"
          />
        </li>
      </ul>
    </div>
    <div v-if="unavailableAwards.length && !user" class="mb-6">
      <h2 class="text-$highlight text-sm mb-2">
        {{
          $auth.loggedIn
            ? $t('awards.unavailable')
            : $t('awards.loginToReceiveAwards')
        }}
      </h2>
      <ul>
        <li>
          <AwardItem
            :award="unavailableAwards[0]"
          />
        </li>
      </ul>
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
      <ul v-if="giftedAwards">
        <li v-for="(award, key) in giftedAwards" :key="key">
          <div v-if="key < 3 || showMoreGifted">
            <AwardItem
              :award="award.award"
              :giver="award.giver.username"
              :winner="award.winner.username"
              :created="new Date(award.created)"
            />
          </div>
        </li>
    </ul>
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
      <ul v-if="receivedAwards">
        <li v-for="(award, key) in receivedAwards" :key="key">
          <div v-if="key < 3 || showMoreReceived">
            <AwardItem
              :award="award.award"
              :giver="award.giver.username"
              :winner="award.winner.username"
              :created="new Date(award.created)"
            />
          </div>
        </li>
      </ul>
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
    user: {
      type: Object,
      default: () => {}
    }
  },
  setup(props) {
    const store = useStore()
    const { fetchAwards, fetchAwarded, fetchAvailableAwards, fetchAvailableSoonAwards, fetchGiftedAwards } = awardApi()
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

      if (!$auth.loggedIn && !props.user) {
        await fetchAwards(props.campaign ? props.campaign.id : null).then(
          function (response) {
            unavailableAwards.value = response
            store.dispatch('awardsChanged')
          }
        )
      } else {
        receivedAwards.value = await fetchAwarded(props.campaign ? props.campaign.id : null, props.user)
        giftedAwards.value = await fetchGiftedAwards(props.campaign ? props.campaign.id : null, props.user)
        /* List unavailble and available Awards on the profile page */
        if ($auth.loggedIn && !props.user && !props.campaign) {
          unavailableAwards.value = await fetchAvailableSoonAwards(0)
          availableAwards.value = await fetchAvailableAwards(0)
        }

        if (props.campaign) {
          unavailableAwards.value = await fetchAvailableSoonAwards(props.campaign.id)
          availableAwards.value = await fetchAvailableAwards(props.campaign.id)
        }
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
