<template>
  <div class="flex flex-col flex-1">
    <div class="page-header px-6 self-start">
      <a class="back-btn" @click.prevent="$emit('closeModal')">
        {{ $t('awards.select') }}
      </a>
    </div>
    <div v-if="availableAwards && availableAwards.length" class="p-6">
      <h2 class="text-$highlight text-sm mb-2">
        {{ $t('awards.available') }}
      </h2>
      <div
        v-for="(award, key) in availableAwards" :key="key"
      >
        <div class="flex flex-row items-center mb-2 award-item cursor-pointer">
          <div class="w-20 h-20 overflow-hidden mr-3 flex-shrink-0">
            <AwardIcon v-if="award.picture" :image="award.picture" :title="award.title" class="h-18 w-auto" />
          </div>
          <div class="flex flex-col flex-grow">
            <p class="mb-1">{{ award.title }}</p>
            <p class="text-$highlight text-sm">
              {{ award.price.toLocaleString() + ' ' + $t('points') }}
            </p>
            <button
              v-if="!award.taken"
              class="btn-outline self-start mt-2 text-xs p-1"
              @click.prevent="awardDetailOpen = award.id"
            >
              {{ $t('awards.gift') }}
            </button>
          </div>
        </div>
        <AwardDetail
          v-if="awardDetailOpen === award.id"
          :award="award"
          :available="true"
          :preselected-user="post.author.uuid"
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
    watch
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
    const awardDetailOpen = ref(null)

    onMounted(async () => {
      await getAvailableAwards()
    })

    watch(
      () => store.getters.awardsChange,
      function () {
        getAvailableAwards()
      }
    )

    async function getAvailableAwards() {
      availableAwards.value = await fetchAvailableAwards(props.post.campaign.id)
    }

    function closeAwardDetail() {
      awardDetailOpen.value = null
      store.dispatch('awardsChange')
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
