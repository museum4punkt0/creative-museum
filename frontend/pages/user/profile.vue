<template>
  <div>
    <div w:grid="~ cols-1 lg:cols-12 lg:gap-4">
      <div w:grid="col-span-3" w:pr="10">
        <div w:p="6" w:display="md:hidden" class="page-header">
          <button class="back-btn" @click.prevent="backButton">
            {{ $t('user.profile.self.headline', {firstName: user.firstName}) }}
          </button>
        </div>

        <img
          v-if="'profilePicture' in user"
          :src="'https://backend.creative-museum.ddev.site' + user.profilePicture.contentUrl"
          w:w="21"
          w:h="21"
          w:mb="4"
          w:rounded="full"
        />

        <h1 w:text="2xl">{{ user.firstName }} {{ user.lastName }}</h1>
        <p class="highlight-text" w:mb="2">@{{ user.username }}</p>
        <p>
          {{ user.description }}
        </p>

        <NuxtLink to="/user/update" w:align="md:self-start" w:mt="10" w:mb="12" class="btn-primary btn-outline"> {{ $t('user.editProfile') }}</NuxtLink>

        <h2 w:text="2xl">{{ $t('user.profile.self.points') }}</h2>

        <div v-for="membership in user.memberships" w:align="self-stretch md:self-start" w:mt="3" w:mb="3">
          <div w:text="sm" w:mb="2" class="highlight-text">{{ membership.campaign.title }}</div>
          <div
            w:px="4"
            w:py="2"
            w:rounded="full"
            w:flex="~ row"
            w:align="items-end"
            w:justify="center"
            class="box-shadow"
          >
            <span w:text="2xl" w:mr="2">{{ membership.score }}</span>
            <span>{{ $t('user.profile.self.score') }}</span>
          </div>
        </div>
      </div>

      <h4>{{ $t('user.profile.self.notifications') }}</h4>

      <h4>{{ $t('user.profile.self.badges.headline') }}</h4>

      <div v-for="achievement in user.achievements">
        <div>
          {{ achievement.badge.title }}<br>
          {{ achievement.badge.description }}<br>
          <img :src="'https://backend.creative-museum.ddev.site' + achievement.badge.picture.contentUrl" />
          {{ $t('user.profile.self.badges.points.' + achievement.badge.type, { threshold: achievement.badge.threshold })}}
        </div>
      </div>

    </div>
  </div>
</template>


<script>

import { defineComponent, useAsync, useRoute, useRouter, computed, useContext, ref, useStore } from '@nuxtjs/composition-api'
import { notificationApi } from '@/api/notification'

export default defineComponent({
  name: 'ProfilePage',
  setup(props) {

    const { getNotifications } = notificationApi()

    const store = useStore()
    const user = computed(() => store.state.auth.user)
    const notifications = getNotifications()

    function backButton() {

    }

    return {
      user,
      backButton,
      notifications
    }
  }
})
</script>
