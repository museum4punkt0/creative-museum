<template>
  <div>
    <div v-if="$auth.loggedIn">
      <div w:mb="10">
        <div w:w="21" w:h="21" w:rounded="full" w:mb="4" class="highlight-bg">
          <img
            src="/images/placeholder_profile.png"
            w:w="21"
            w:h="21"
            w:object="contain center"
            w:rounded="full"
          />
        </div>
        <p w:text="2xl">
          {{ fullName }}
        </p>
        <p w:text="lg" w:mb="3" class="highlight-text">
          ### TITEL / RANG ###
        </p>
        <p v-if="$auth.user.description">
          {{ $auth.user.description }}
        </p>
        <NuxtLink
          to="/user/profile"
          w:mt="10"
          w:py="2"
          w:w="full"
          class="btn-outline"
        >
          {{ $t('user.editProfile') }}
        </NuxtLink>
      </div>
      <div w:mb="10">
        <p w:font="bold" w:mb="3">
          {{ $t('user.score') }}
        </p>
        <UserScore :campaign="campaign" />
      </div>
    </div>
    <div w:mb="10">
      <CampaignFilter :campaign="campaign" />
    </div>
    <div v-if="isLargerThanLg">
      <div w:mb="10">
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
    return {
      fullName,
      campaignScore: computed(() =>
        context.$auth.$storage.getState('campaignScore')
      ),
      isLargerThanLg,
    }
  },
})
</script>
