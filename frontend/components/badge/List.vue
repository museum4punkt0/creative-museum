<template>
  <div>
    <div v-if="$auth.user && 'achievements' in $auth.user" class="flex flex-row justify-between mb-10">
      <h2 class="text-2xl">{{ $t('user.profile.badges.headline') }}</h2>
      <button
        v-if="!campaign && $auth.user.achievements.length > 2 || campaign && badgesAndAchievements.length > 2"
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

    <div v-if="$auth.loggedIn && !campaign">
      <div
        v-for="(achievement, key) in $auth.user.achievements"
        :key="key"
      >
        <div v-if="key < 2 || readMore">
          <BadgeItem :badge="achievement.badge" />
        </div>
      </div>
    </div>
    <div v-else-if="$auth.loggedIn">
      <div
        v-for="(badge, key) in badgesAndAchievements"
        :key="key"
      >
        <div v-if="key < 2 || readMore">
          <BadgeItem :badge="badge" :class="achievementIds.includes(badge.id) ? 'opacity-100' : 'opacity-50 hover:opacity-100'" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  useContext,
  onMounted
} from '@nuxtjs/composition-api'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'
import { badgeApi } from '@/api/badge'

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
  setup(props) {
    const { $auth } = useContext()
    const { fetchBadges } = badgeApi()

    const readMore = ref(false)
    const achievementIds = ref([])
    const badges = ref(null)
    const badgesAndAchievements = ref([])

    function toggleShowMore() {
      readMore.value = !readMore.value
    }
    onMounted(async () => {

      if ($auth.user && props.campaign) {
        $auth.user.achievements.forEach((item) => {
          if (item.badge.campaign.id === props.campaign.id) {
            achievementIds.value.push(item.badge.id)
          }
        })
      }

      badges.value = await fetchBadges(props.campaign ? props.campaign.id : null)

      badges.value.forEach(function(item) {
        if (achievementIds.value.includes(item.id)) {
          badgesAndAchievements.value = [item, ...badgesAndAchievements.value]
        } else {
          badgesAndAchievements.value = [...badgesAndAchievements.value, item]
        }
      })

    })

    return {
      readMore,
      badges,
      badgesAndAchievements,
      achievementIds,
      toggleShowMore
    }
  },
})
</script>
