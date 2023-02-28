<template>
  <div>
    <div class="page-header md:hidden">
      <button type="button" class="back-btn" @click.prevent="backButton">
        {{
          $t('prev')
        }}
      </button>
    </div>

    <template v-if="$auth.loggedIn || userData">
      <div
        class="rounded-full mb-4 h-21 w-21 bg-$highlight p-px overflow-hidden"
      >
        <img
          v-if="'profilePicture' in userData"
          :src="`${backendURL}/${userData.profilePicture.contentUrl}`"
          class="rounded-full mb-4 object-cover object-center w-20.5 h-20.5"
        />
      </div>

      <div class="mb-10">
        <h2 class="text-2xl">
          {{ !userData.deleted ? `@${userData.username}` : $userName(userData) }}
        </h2>
        <p v-if="userData.achievements.length" class="highlight-text mb-3">
          {{ userData.achievements[0].badge.title }} @{{ userData.username }}
        </p>
        <p>{{ userData.description }}</p>
      </div>

      <button
        v-if="$auth.loggedIn && !user"
        class="btn-primary btn-outline md:self-start cursor-pointer w-full"
        @click="showProfileUpdate"
      >
        {{ $t('user.editProfile') }}</button
      >

      <div v-if="userData.memberships.length">
        <h2 class="font-bold mb-3 mt-12">{{ $t('score') }}</h2>
        <ul>
          <li v-for="(membership, key) in userData.memberships" :key="key" class="mb-4">
            <NuxtLink
              :to="localePath('/campaigns/' + membership.campaign.id)"
              class="self-stretch md:self-start mt-4"
              :style="`--highlight: ${membership.campaign.color};`"
            >
              <h3 class="text-$highlight text-sm mb-2">
                {{ membership.campaign.title }}
              </h3>
              <div
                class="box-shadow justify-center items-end flex flex-row rounded-full py-2 px-4"
              >
                <span class="text-2xl mr-2">{{
                  membership.score.toLocaleString()
                }}</span>
                <span>{{ $t('points') }}</span>
              </div>
            </NuxtLink>
          </li>
        </ul>
      </div>
    </template>
    <div v-if="isLargerThanLg">
      <div class="mt-10">
        <PageFooter />
      </div>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  useContext,
  computed,
  useStore,
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    user: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const { $auth, $config, $breakpoints } = useContext()
    const store = useStore()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const userData = computed(() => {
      if (props.user) {
        return props.user
      } else if ($auth.user) {
        return $auth.user
      } else {
        return null
      }
    })

    function showProfileUpdate() {
      store.dispatch('showProfileUpdate')
    }

    function backButton() {
      history.back()
    }

    return {
      userData,
      isLargerThanLg,
      backendURL: $config.backendURL,
      showProfileUpdate,
      backButton,
    }
  },
})
</script>
