<template>
  <div class="flex flex-col flex-1">
    <div class="page-header px-6 self-start">
      <a class="back-btn" @click.prevent="$emit('closeModal')">
        {{ $t('awards.select') }}
      </a>
    </div>
    <div v-if="availableAwards && availableAwards.length" class="p-6">
      <div class="text-$highlight text-sm mb-2">
        {{ $t('awards.available') }}
      </div>
      <div
        v-for="(award, key) in availableAwards" :key="key"
        class="flex flex-row items-center mb-2 award-item cursor-pointer"
      >
        <div>
          <div class="w-20 h-20 overflow-hidden mr-3 flex-shrink-0">
            <img
              v-if="award.picture"
              :src="`${backendURL}/${award.picture.contentUrl}`"
              :alt="award.title"
              class="max-w-18 h-auto"
            />
          </div>
          <div class="flex flex-col flex-grow">
            <p class="mb-1">{{ award.title }}</p>
            <p class="text-$highlight text-sm">
              {{ award.price.toLocaleString() + ' ' + $t('points') }}
            </p>
            <button
              v-if="award.available && !award.taken"
              class="btn-outline self-start mt-2 text-xs p-1"
              @click.prevent="awardDetailOpen = true"
            >
              {{ $t('awards.gift') }}
            </button>
          </div>
        </div>
        <AwardDetail
          v-if="awardDetailOpen === true"
          :award="award"
          class="absolute left-0 top-0 bottom-0 right-0 z-20 bg-grey"
          @closeAwardDetail="closeAwardDetail"
        />
      </div>
    </div>
    <div v-else class="p-6">
      {{ $t('awards.noAvailable') }}
    </div>
  </div>
</template>
<script>
import {
    defineComponent,
    useContext,
    onMounted,
    useStore,
    ref,
  } from '@nuxtjs/composition-api'
import { awardApi } from '@/api/award'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  emits: [
    'closeModal'
  ],
  setup(props) {
    const context = useContext()
    const store = useStore()
    const { fetchAvailableAwards } = awardApi()
    const availableAwards = ref(null)
    const awardDetailOpen = ref(false)

    onMounted(async () => {
      await getAvailableAwards()
    })

    async function getAvailableAwards() {
      availableAwards.value = await fetchAvailableAwards(props.post.campaign.id)
    }

    function closeAwardDetail() {
      awardDetailOpen.value = false
      store.dispath('awardsChange')
    }

    return {
      availableAwards,
      awardDetailOpen,
      getAvailableAwards,
      closeAwardDetail,
      backendURL: context.$config.backendURL,
    }
  },
})
</script>
