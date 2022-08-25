<template>
  <div>
    <div
      class="snap snap-mandatory snap-x scrollbar-hide relative -mx-6 lg:mx-0 px-6 lg:px-0 flex flew-row flex-nowrap lg:flex-col overflow-x-auto"
      :class="dropdownHeight ? 'h-20 lg:h-auto' : 'h-auto'"
    >
      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 py-1 px-2"
        @click.prevent="resetFilter"
        :class="currentSorting === 'date' ? 'active' : ''"
        type="button"
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
        type="button"
      >
        {{ $t('filter.controversial') }}
      </button>

      <DropDown
        v-if="
          campaign &&
          campaign.feedbackOptions &&
          campaign.feedbackOptions.length > 0
        "
        :options="campaign.feedbackOptions"
        label="Feedback"
        class="ml-3 lg:ml-0"
        @dropdownState="setHeight"
      />

      <button
        class="btn-outline border-border-1 border-white text-sm self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'playlist' ? 'active' : ''"
        type="button"
        @click.prevent="togglePlaylistFilter"
      >
        {{ $t('filter.playlist') }}
      </button>

      <a v-if="reversable" @click.prevent="changeSortDirection">Sortierung umkehren</a>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup() {

    const context = useContext()

    const currentSorting = computed(() => context.store.state.currentSorting)

    const reversable = computed(() => {
      const reversableProps = [
        'date',
        'playlist',
        'votestotal',
      ]
      return reversableProps.includes(context.store.state.currentSorting)
    })

    const dropdownHeight = ref(false)

    function toggleRelevanceFilter() {
      if (currentSorting.value === 'votestotal') {
        resetFilter()
        return
      }
      context.store.dispatch(
        'setCurrentSortingWithDirection',
        ['votestotal', 'desc']
      )
    }

    function resetFilter() {
      context.store.dispatch(
        'setCurrentSortingWithDirection',
        ['date', 'desc']
      )
    }

    function togglePlaylistFilter() {
      if (currentSorting.value === 'playlist') {
        resetFilter()
        return
      }
      context.store.dispatch(
        'setCurrentSortingWithDirection',
        ['playlist', 'desc']
      )
    }

    function setHeight(params) {
      if (params === true) {
        dropdownHeight.value = true
      } else {
        dropdownHeight.value = false
      }
    }

    function changeSortDirection() {
      context.store.dispatch(
        'setCurrentSortingDirection',
        context.store.state.currentSortingDirection === 'asc' ? 'desc' : 'asc'
      )
    }

    return {
      dropdownHeight,
      setHeight,
      reversable,
      changeSortDirection,
      togglePlaylistFilter,
      toggleRelevanceFilter,
      resetFilter,
      currentSorting
    }
  },
})
</script>
