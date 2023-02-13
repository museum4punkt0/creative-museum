<template>
  <div class="relative">
      <button
        class="btn-outline border-border-1 border-white text-lg block w-full text-center py-3 self-start rounded-full mb-3 py-1 px-2"
        :class="currentSorting === 'date' ? 'active' : ''"
        :aria-current="currentSorting === 'date' ? 'true' : 'false'"
        type="button"
        @click.prevent="resetFilter"
      >
        {{ $t('filter.newest') }}
      </button>
      <button
        class="btn-outline border-border-1 border-white text-lg block w-full text-center py-3 self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'votestotal' ? 'active' : ''"
        :aria-current="currentSorting === 'votestotal' ? 'true' : 'false'"
        type="button"
        @click.prevent="toggleRelevanceFilter"
      >
        {{ $t('filter.relevant') }}
      </button>
      <button
        class="btn-outline border-border-1 border-white text-lg block w-full text-center py-3 self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'controversial' ? 'active' : ''"
        aria-haspopup="true"
        type="button"
        @click.prevent="toggleControversialFilter"
      >
        {{ $t('filter.controversial') }}
      </button>
  </div>
</template>
<script>
import {
  defineComponent,
  useContext,
  computed,
} from '@nuxtjs/composition-api'
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
        'controversial',
      ]
      return reversableProps.includes(context.store.state.currentSorting)
    })

    resetFilter()

    function toggleRelevanceFilter() {
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
      context.store.dispatch('setCurrentSortingWithDirection', ['date', 'desc'])
    }

    return {
      reversable,
      toggleRelevanceFilter,
      toggleControversialFilter,
      resetFilter,
      currentSorting,
    }
  },
})
</script>
