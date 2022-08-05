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
      <div w:grid="col-span-6" w:pr="10"></div>
      <div w:grid="col-span-3" w:pr="10">
        <div w:mb="12">
          <h2 w:text="2xl">{{ $t('user.profile.self.notifications') }}</h2>

        </div>
        <div w:mb="12">
          <h2 w:text="2xl">{{ $t('user.profile.self.awards') }}</h2>

        </div>
        <div w:mb="12">
          <div w:flex="~ row" w:justify="content-between">
            <h2 w:text="2xl">{{ $t('user.profile.self.badges.headline') }}</h2>
            <button class="highlight-text" w:text="sm" w:flex="~ row" w:align="items-center" w:font="leading-none" w:cursor="pointer" @click.prevent="showMore">
              <svg w:w="2" w:h="auto" w:mr="2" viewBox="0 0 9 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.20132 10.2219V0H4.17333V10.2218L1.36361 7.56731L0.636719 8.25405L4.32389 11.7376L4.68733 12.0809L5.05078 11.7376L8.72239 8.26875L7.9955 7.582L5.20132 10.2219Z"/>
              </svg>
              <span>Alle anzeigen</span>
            </button>
          </div>

          <div v-for="achievement in user.achievements" w:flex="~ row" w:mb="6">
            <img :src="'https://backend.creative-museum.ddev.site' + achievement.badge.picture.contentUrl" w:w="20" w:align="self-center" />
            <div w:ml="4" w:flex="~ col" w:justify="content-center">
              <span>{{ achievement.badge.title }}</span>
              <span w:text="sm" class="highlight-text">{{ $t('user.profile.self.badges.points.' + achievement.badge.type, { threshold: achievement.badge.threshold })}}</span>
            </div>
          </div>
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

    const readMore = computed(false)
    const store = useStore()
    const user = computed(() => store.state.auth.user)
    const notifications = getNotifications()

    function backButton() {

    }

    function showMore() {
      readMore.value = true;
    }

    return {
      user,
      backButton,
      notifications,
      showMore
    }
  }
})
</script>
