<template>
  <div>
    <div v-if="$auth.loggedIn">
      <div class="mb-10" >
        <div class="highlight-bg w-21 h-21 rounded-full mb-4">
          <img
            :src="profilePicture"
            class="w-21 h-21 object-contain object-center rounded-full"
          />
        </div>
        <p class="text-2xl">{{ $auth.user.firstName }} {{ $auth.user.lastName }}</p>
        <p class="highlight-text text-lg mb-3">@{{ fullName }}</p>
        <p v-if="$auth.user.description">
          {{ $auth.user.description }}
        </p>
        <NuxtLink
          to="/user/profile"
          class="btn-outline mt-10 py-2 w-full"
        >
          {{ $t('user.editProfile') }}
        </NuxtLink>
      </div>
      <div class="mb-10">
        <p class="font-bold mb-3">
          {{ $t('user.score') }}
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
      default: () => {},
    },
  },
  setup() {
    const { $auth, $breakpoints } = useContext()
    const fullName = computed(() => {
      return $auth.user
        ? $auth.user.firstName
          ? $auth.user.firstName
          : '' + $auth.user.lastName
          ? ' ' + $auth.user.lastName
          : ''
        : ''
    })
    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const profilePicture = computed(() => {
      if ('profilePicture' in $auth.user) {
        return (
          'https://backend.creative-museum.ddev.site' +
          $auth.user.profilePicture.contentUrl
        )
      }
      return '/images/placeholder_profile.png'
    })

    return {
      fullName,
      profilePicture,
      campaignScore: computed(() =>
        context.$auth.$storage.getState('campaignScore')
      ),
      isLargerThanLg,
    }
  },
})
</script>
