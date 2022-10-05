<template>
  <div class="relative">
    <div
      class="snap snap-mandatory snap-x scrollbar-hide -mx-6 lg:mx-0 px-6 lg:px-0 flex flew-row flex-nowrap lg:flex-col overflow-x-auto"
      :class="[dropdownHeight ? 'h-20 lg:h-auto' : 'h-auto', dropdownHeight ? 'overflow-x-hidden' : '']"
    >
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 py-1 px-2"
        :class="currentSorting === 'date' ? 'active' : ''"
        type="button"
        @click.prevent="resetFilter"
      >
        {{ $t('filter.newest') }}
      </button>
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'votestotal' ? 'active' : ''"
        type="button"
        @click.prevent="toggleRelevanceFilter"
      >
        {{ $t('filter.relevant') }}
      </button>
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'controversial' ? 'active' : ''"
        type="button"
        @click.prevent="toggleControversialFilter"
      >
        {{ $t('filter.controversial') }}
      </button>

      <UtilitiesDropDown
        v-if="
          campaign &&
          campaign.feedbackOptions &&
          campaign.feedbackOptions.length > 0
        "
        ref="feedbacksDropdown"
        :options="campaign.feedbackOptions"
        label="Feedback"
        class="ml-3 lg:ml-0"
        @dropdownState="setHeight"
        @dropdownChange="setFeedbackFilter"
      />

      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'playlist' ? 'active' : ''"
        type="button"
        @click.prevent="togglePlaylistFilter"
      >
        {{ $t('filter.playlist') }}
      </button>
    </div>
    <div class="mt-2">
      <a
        v-if="reversable"
        class="text-xs text-$highlight flex flex-row items-center justify-end cursor-pointer"
        @click.prevent="changeSortDirection"
      >
        <ReverseSortingIcon class="h-4 w-auto inline-block mr-2 transform-gpu duration-200" :class="currentSortingDirection === 'asc' ? 'rotate-180' : ''" />
        <span>{{ $t('filter.order.reverse') }}</span>
      </a>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  useContext,
  computed
} from '@nuxtjs/composition-api'
import ReverseSortingIcon from '@/assets/icons/reverseSorting.svg?inline'
export default defineComponent({
  components: {
    ReverseSortingIcon,
  },
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup() {
    const context = useContext()
    const feedbacksDropdown = ref(null)
    const drodownOpen = ref(false)

    const currentSorting = computed(() => context.store.state.currentSorting)
    const currentSortingDirection = computed(() => context.store.state.currentSortingDirection)

    const reversable = computed(() => {
      const reversableProps = [
        'date',
        'playlist',
        'votestotal',
        'controversial',
      ]
      return reversableProps.includes(context.store.state.currentSorting)
    })

    const dropdownHeight = ref(false)

    resetFilter()

    function setFeedbackFilter(id) {
      if (
        currentSorting.value === 'feedback' &&
        context.store.state.filterId === id
      ) {
        resetFilter()
        return
      }
      context.store.dispatch('setSortByCampaignFeedback', id)
    }

    function toggleRelevanceFilter() {
      if (feedbacksDropdown.value) {
        feedbacksDropdown.value.closeDropdown()
      }
      if (currentSorting.value === 'votestotal') {
        resetFilter()
        return
      }
      context.store.dispatch('setCurrentSortingWithDirection', [
        'votestotal',
        'desc',
      ])
    }

    function toggleControversialFilter() {
      if (feedbacksDropdown.value) {
        feedbacksDropdown.value.closeDropdown()
      }
      if (currentSorting.value === 'controversial') {
        resetFilter()
        return
      }
      context.store.dispatch('setCurrentSortingWithDirection', [
        'controversial',
        'desc',
      ])
    }

    function resetFilter() {
      if (feedbacksDropdown.value) {
        feedbacksDropdown.value.closeDropdown()
      }
      context.store.dispatch('setCurrentSortingWithDirection', ['date', 'desc'])
    }

    function togglePlaylistFilter() {
      if (feedbacksDropdown.value) {
        feedbacksDropdown.value.closeDropdown()
      }
      if (currentSorting.value === 'playlist') {
        resetFilter()
        return
      }
      context.store.dispatch('setCurrentSortingWithDirection', [
        'playlist',
        'desc',
      ])
    }

    function setHeight(params) {
      dropdownHeight.value = params === true
    }

    function changeSortDirection() {
      context.store.dispatch(
        'setCurrentSortingDirection',
        context.store.state.currentSortingDirection === 'asc' ? 'desc' : 'asc'
      )
    }

    return {
      dropdownHeight,
      drodownOpen,
      setHeight,
      reversable,
      changeSortDirection,
      togglePlaylistFilter,
      toggleRelevanceFilter,
      toggleControversialFilter,
      resetFilter,
      currentSorting,
      currentSortingDirection,
      feedbacksDropdown,
      setFeedbackFilter,
    }
  },
})
</script>
