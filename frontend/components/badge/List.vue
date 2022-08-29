<template>
  <div>
    <div class="flex flex-row justify-between">
      <h2 class="text-2xl">{{ $t('user.profile.self.badges.headline') }}</h2>

      <button
        class="highlight-text text-sm flex flex-row items-center leading-none cursor-pointer"
        @click.prevent="toggleShowMore"
      >
        <ArrowIcon
          class="relative w-2 top-0 mr-0.5 inline-block transition-all duration-200"
          :class="readMore ? 'transform-gpu rotate-180' : ''"
        />
        <span v-if="!readMore">{{ $t('showAll') }}</span>
        <span v-else>{{ $t('hide') }}</span>
      </button>
    </div>

    <div v-if="$auth.loggedIn">
      <div
        v-for="(achievement, key) in user.achievements"
        :key="key"
        class="flex flex-row mb-6"
      >
        <div v-if="key < 2 || readMore">
          <img
            v-if="'picture' in achievement.badge"
            :src="`${backendUrl}/${achievement.badge.picture.contentUrl}`"
            class="self-center w-20"
          />
          <div class="content-center flex flex-col ml-4">
            <span>{{ achievement.badge.title }}</span>
            <span class="highlight-text text-sm">{{
              $t('user.profile.self.badges.points.' + achievement.badge.type, {
                threshold: achievement.badge.threshold,
              })
            }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  useStore,
  ref,
  computed,
} from '@nuxtjs/composition-api'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'

export default defineComponent({
  components: {
    ArrowIcon,
  },
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup() {
    const store = useStore()
    const user = computed(() => store.state.auth.user)

    const readMore = ref(false)

    function toggleShowMore() {
      readMore.value = !readMore.value
    }

    return {
      user,
      readMore,
      toggleShowMore,
    }
  },
})
</script>
