<template>
  <div>
    <div v-if="$auth.loggedIn">
      <div class="mb-10">
        <div
          class="rounded-full mb-4 h-21 w-21 bg-$highlight p-px overflow-hidden"
        >
          <img
            v-if="profilePicture"
            :src="profilePicture"
            class="rounded-full mb-4 cover w-full h-full"
          />
        </div>
        <p class="text-2xl">{{ $auth.user.fullName }}</p>
        <p
          v-if="$auth.user.achievements.length"
          class="highlight-text text-lg mb-3"
        >
          {{ $auth.user.achievements[0].badge.title }} @{{
            $auth.user.username
          }}
        </p>
        <p v-if="$auth.user.description">
          {{ $auth.user.description }}
        </p>
        <NuxtLink to="/user/profile" class="btn-outline mt-10 py-2 w-full">
          {{ $t('user.showProfile') }}
        </NuxtLink>
      </div>
      <div class="mb-10">
        <p class="font-bold mb-3">
          {{ $t('score') }}
        </p>
        <UserScore :campaign="campaign" />
      </div>
    </div>
    <div class="mb-10">
      <CampaignFilter :campaign="campaign" />
    </div>
    <div v-if="isLargerThanLg">
      <div class="mb-10">
        <PageFooter />
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const { $auth, $breakpoints, $config } = useContext()
    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const profilePicture = computed(() => {
      if ($auth.loggedIn && 'profilePicture' in $auth.user) {
        return `${$config.backendURL}/${$auth.user.profilePicture.contentUrl}`
      }
      return '/images/placeholder_profile.png'
    })

    return {
      profilePicture,
      isLargerThanLg,
      backendURL: $config.backendURL,
    }
  },
})
</script>
