<template>
  <div>
    <client-only>
      <NuxtLink
        v-if="user"
        to="/user/profile"
        class="flex flex-row md:space-x-4 items-center"
      >
        <div class="relative">
          <img
            :src="profilePicture"
            class="w-6 h-6 object-cover object-center rounded-full"
          />
          <span
            v-if="showNotificationBubble"
            class="highlight-bg absolute h-2 w-2 top-0 right-0 rounded-full"
          />
          <span
            v-if="campaignScore"
            class="highlight-bg absolute top-1 py-0.5 px-2 -mr-1 right-full rounded-xl text-xs text-black whitespace-nowrap"
            >{{ campaignScore.toLocaleString() }} P</span
          >
        </div>
        <span
          class="text-sm overflow-ellipsis hidden md:inline-block overflow-hidden min-w-24 max-w-32"
        >
          {{
            user && user.username
              ? `@${user.username}`
              : username
              ? `@${username}`
              : $t('noUsername')
          }}
        </span>
      </NuxtLink>

      <transition
        enter-active-class="duration-300 ease-out opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="duration-200 ease-in"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <UtilitiesModal v-if="user && !user.username">
          <div class="flex flex-col flex-1 justify-between">
            <div>
              <h1 class="page-header px-6">
                {{ $t('provideUsername.title') }}
              </h1>
              <div class="px-6 pb-4">
                <input
                  v-model="username"
                  type="text"
                  class="input-text p-4"
                  :class="violations ? 'border border-red-500' : ''"
                  placeholder="Username*"
                  @keyup="violations = null"
                />
                <div
                  v-for="(violation, key) in violations"
                  :key="key"
                  class="px-6 py-2 text-red-500"
                >
                  {{ $t(`user.violation.${violation.code}`) }}
                </div>
              </div>
            </div>
            <div class="p-6 mt-auto">
              <button
                v-show="username.length > 3"
                class="btn-primary w-full"
                type="button"
                @click.prevent="submitUsername"
              >
                {{ $t('submit') }}
              </button>
            </div>
          </div>
        </UtilitiesModal>
      </transition>
    </client-only>
  </div>
</template>

<script>
import {
  useStore,
  useContext,
  computed,
  defineComponent,
  ref,
  watch
} from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  setup() {
    const context = useContext()
    const store = useStore()
    const username = ref('')
    const violations = ref(null)
    const showNotificationBubble = ref(false)
    const { supplyUsername } = userApi()

    function submitUsername() {
      supplyUsername(username.value).then(function (response) {
        context.$auth.fetchUser()
        violations.value = response.error.response.data.violations
      })
    }

    const campaignScore = computed(() => {
      if (!context.$auth.loggedIn || !store.state.currentCampaign) {
        return null
      }
      if (!context.$auth.user.hasOwnProperty('memberships')) {
        return null
      }
      for (const id in context.$auth.user.memberships) {
        const membership = context.$auth.user.memberships[id]
        if (membership.campaign.id !== parseInt(store.state.currentCampaign)) {
          continue
        }
        return membership.score
      }
      return 0
    })

    const profilePicture = computed(() => {
      if (context.$auth.user && 'profilePicture' in context.$auth.user) {
        return `${context.$config.backendURL}/${context.$auth.user.profilePicture.contentUrl}`
      }
      return '/images/placeholder_profile.png'
    })

    watch(
      () => store.getters.notificationsCount,
      function (newVal) {
        if (newVal > 0) {
          showNotificationBubble.value = true
        } else {
          showNotificationBubble.value = false
        }
      }
    )

    return {
      user: computed(() => store.state.auth.user),
      campaignScore,
      username,
      violations,
      profilePicture,
      showNotificationBubble,
      submitUsername,
    }
  },
})
</script>
