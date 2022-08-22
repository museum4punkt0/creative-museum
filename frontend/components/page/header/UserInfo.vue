<template>
  <div>
    <div v-show="user" class="flex flex-row md:space-x-4 items-center">
      <ClientOnly>
        <div class="relative">
          <img
            :src="profilePicture"
            class="w-6 h-6 object-contain object-center rounded-full"
          />
          <span
            class="highlight-bg absolute h-2 w-2 top-0 right-0 rounded-full"
          />
          <span
            v-if="campaignScore && campaignScore.value"
            class="highlight-bg absolute top-1 py-0.5 px-2 -mr-1 right-full rounded-xl text-xs text-black whitespace-nowrap"
            >{{ campaignScore.value.score.toLocaleString() }} P</span
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
      </ClientOnly>
    </div>
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <Modal v-if="user && !user.username" t="10">
        <div class="flex flex-col flex-1 justify-between">
          <div>
            <h1 class="page-header p-4">
              {{ $t('provideUsername.title') }}
            </h1>
            <div class="px-4 pb-4">
              <input
                v-model="username"
                type="text"
                class="input-text p-4"
                placeholder="Username*"
              />
            </div>
          </div>
          <div class="p-6 mt-auto">
            <button
              v-show="username.length > 3"
              class="btn-primary w-full"
              @click.prevent="submitUsername"
            >
              {{ $t('submit') }}
            </button>
          </div>
        </div>
      </Modal>
    </transition>
  </div>
</template>
<script>
import {
  useStore,
  useContext,
  computed,
  defineComponent,
  ref,
} from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  setup() {
    const context = useContext()
    const store = useStore()
    const username = ref('')
    const { supplyUsername } = userApi()

    function submitUsername() {
      supplyUsername(username.value).then(function () {
        context.$auth.fetchUser()
      })
    }

    const profilePicture = computed(() => {
      if (context.$auth.user && 'profilePicture' in context.$auth.user) {
        return (
          'https://backend.creative-museum.ddev.site/' +
          context.$auth.user.profilePicture.contentUrl
        )
      }
      return '/images/placeholder_profile.png'
    })

    return {
      user: computed(() => store.state.auth.user),
      campaignScore: computed(() =>
        context.$auth.$storage.getState('campaignScore')
      ),
      username,
      submitUsername,
      profilePicture,
    }
  },
})
</script>
