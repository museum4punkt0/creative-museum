<template>
  <div>
    <div class="page-header p-6 md:hidden">
      <button type="button" class="back-btn" @click.prevent="backButton">
        {{
          $auth.loggedIn ? $t('user.profile.self.headline', { firstName: $auth.user.firstName }) : userData ? userData.firstname + ' ' + userData.lastname : ''
        }}
      </button>
    </div>

    <template v-if="$auth.loggedIn || userData">

      <div class="rounded-full mb-4 h-21 w-21 bg-$highlight p-px overflow-hidden">
        <img
          v-if="'profilePicture' in userData"
          :src="`${backendUrl}/${userData.profilePicture.contentUrl}`"
          class="rounded-full mb-4 cover w-full h-full"
        />
      </div>

      <div class="mb-10">
        <h1 class="text-2xl">
          {{ userData.firstName }} {{ userData.lastName }}
        </h1>
        <p class="highlight-text mb-2">@{{ userData.username }}</p>
        <p>{{ userData.description }}</p>
      </div>

      <a
        v-if="$auth.loggedIn && !user"
        class="btn-primary btn-outline md:self-start cursor-pointer"
        @click="showProfileUpdate"
      >
        {{ $t('user.editProfile') }}</a
      >

      <div v-if="userData.memberships.length">
        <h2  class="font-bold mb-3 mt-12">{{ $t('score') }}</h2>
        <div
          v-for="(membership, key) in userData.memberships"
          :key="key"
          class="self-stretch md:self-start mt-4"
          :style="`--highlight: ${membership.campaign.color};`"
        >
          <div class="text-$highlight text-sm mb-2">
            {{ membership.campaign.title }}
          </div>
          <div
            class="box-shadow justify-center items-end flex flex-row rounded-full py-2 px-4"
          >
            <span class="text-2xl mr-2">{{
              membership.score.toLocaleString()
            }}</span>
            <span>{{ $t('points') }}</span>
          </div>
        </div>
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
import { defineComponent, useContext, computed, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    user: {
      type: Object,
      default: () => {}
    }
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

    return {
      userData,
      isLargerThanLg,
      backendUrl: $config.backendUrl,
      showProfileUpdate
    }

  },
})
</script>
