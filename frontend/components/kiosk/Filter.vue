<template>
  <div class="relative">
      <div
        class="border-1 text-lg block w-full text-center py-3 self-start rounded-full mb-3 py-1 px-2"
        :class="currentSorting === 'date' ? 'border-$highlight bg-$highlight' : 'border-white'"
        :aria-current="currentSorting === 'date' ? 'true' : 'false'"
      >
        {{ $t('filter.newest') }}
      </div>
      <div
        class="border-1  text-lg block w-full text-center py-3 self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'votestotal' ? 'border-$highlight bg-$highlight' : 'border-white'"
        :aria-current="currentSorting === 'votestotal' ? 'true' : 'false'"
      >
        {{ $t('filter.relevant') }}
      </div>
      <div
        class="border-1 text-lg block w-full text-center py-3 self-start rounded-full mb-3 ml-3 lg:ml-0 py-1 px-2"
        :class="currentSorting === 'controversial' ? 'border-$highlight bg-$highlight' : 'border-white'"
        aria-haspopup="true"
      >
        {{ $t('filter.controversial') }}
      </div>
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
