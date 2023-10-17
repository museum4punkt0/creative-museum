<template>
  <div v-if="(!$auth.loggedIn && badges && badges.length) || ($auth.loggedIn && badgesAndAchievements.length) || ($auth.loggedIn && badges && badges.length && !campaign)">
    <div class="flex flex-row justify-between mb-10">
      <h2 class="text-2xl">{{ $t('user.profile.badges.headline') }}</h2>
      <button
        v-if="
          ($auth.loggedIn && !user  && (!campaign && $auth.user.achievements.length > 2)) ||
          (campaign && badgesAndAchievements.length > 2) ||
          (user && user.achievements.length > 2) ||
          (!user && !$auth.loggedIn && badges.length > 2)
        "
        class="highlight-text text-sm flex flex-row items-center leading-none cursor-pointer whitespace-nowrap border border-transparent rounded-xl p-1 -mt-1 -mb-1 focus-visible:border focus-visible:border-white"
        @click.prevent="showMoreBadges = !showMoreBadges"
      >
        <ArrowIcon
          class="relative w-2 top-0 mr-0.5 inline-block transition-all duration-200"
          :class="showMoreBadges ? 'transform-gpu rotate-180' : ''"
        />
        <span v-if="!showMoreBadges">{{ $t('showAll') }}</span>
        <span v-else>{{ $t('hide') }}</span>
      </button>
    </div>
    <div v-if="user">
      <div v-for="(achievement, key) in badges" :key="key">
        <div v-if="key < 2 || showMoreBadges">
          <BadgeItem :badge="achievement.badge" />
        </div>
      </div>
    </div>
    <div v-else-if="($auth.loggedIn && !campaign) || user">
      <div v-for="(achievement, key) in $auth.user.achievements" :key="key">
        <div v-if="key < 2 || showMoreBadges">
          <BadgeItem :badge="achievement.badge" />
        </div>
      </div>
    </div>
    <div v-else-if="$auth.loggedIn">
      <div v-for="(badge, key) in badgesAndAchievements" :key="key">
        <div v-if="key < 2 || showMoreBadges">
          <BadgeItem
            :badge="badge"
            :class="
              achievementIds.includes(badge.id)
                ? 'opacity-100'
                : 'opacity-50 hover:opacity-100'
            "
          />
        </div>
      </div>
    </div>
    <div v-else>
      <div v-for="(badge, key) in badges" :key="key">
        <div v-if="key < 2 || showMoreBadges">
          <BadgeItem :badge="badge" />
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
  onMounted,
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
    user: {
      type: Object,
      default: () => {}
    }
  },
  setup(props) {
    const { $auth } = useContext()
    const { fetchBadges, fetchBadged } = badgeApi()

    const showMoreBadges = ref(false)
    const achievementIds = ref([])
    const badges = ref(null)
    const badgesAndAchievements = ref([])

    onMounted(async () => {
      if ($auth.user && props.campaign) {
        $auth.user.achievements.forEach((item) => {
          if (item.badge.campaign.id === props.campaign.id) {
            achievementIds.value.push(item.badge.id)
          }
        })
      }

      if (props.user) {
        badges.value = await fetchBadged(props.user)
      } else {
        badges.value = await fetchBadges(
          props.campaign ? props.campaign.id : null
        )
      }

      badges.value.forEach(function (item) {
        if (achievementIds.value.includes(item.id)) {
          badgesAndAchievements.value = [item, ...badgesAndAchievements.value]
        }
      })
    })

    return {
      showMoreBadges,
      badges,
      badgesAndAchievements,
      achievementIds,
    }
  },
})
</script>
