<template>
  <div class="lg:grid lg:grid-cols-4 p-6 container text-white">
    <div class="mb-10 lg:mb-0">
      <client-only>
        <a
          v-if="!$auth.loggedIn"
          href="#"
          class="flex flex-row items-center font-bold leading-loose cursor-pointer"
          @click.prevent="login"
        >
          <LoginIcon class="w-6 h-6 mr-2" />
          <span>Login</span>
        </a>
        <a
          v-else
          href="#"
          class="flex flex-row items-center font-bold leading-loose cursor-pointer"
          @click.prevent="logout"
        >
          <LogoutIcon class="mr-2 w-auto" />
          <span>Logout</span>
        </a>
      </client-only>
    </div>
    <nav class="mb-10 lg:mb-0" role="navigation">
      <NuxtLink
        v-if="$auth.loggedIn"
        to="/user/profile"
        class="block text-lg font-bold leading-loose mb-4"
        @click.native="closeMenu"
      >
        {{ $t('navigation.profile.header') }}
      </NuxtLink>
      <a
        v-if="$auth.loggedIn"
        href="#"
        class="block mb-4 cursor-pointer"
        @click.prevent="showProfileUpdate"
        >{{ $t('navigation.profile.settings') }}</a
      >
      <NuxtLink
        v-if="$auth.loggedIn"
        to="/user/search"
        class="block mb-4"
        @click.native="closeMenu"
        >{{ $t('navigation.profile.search') }}</NuxtLink
      >
      <NuxtLink
        v-if="$auth.loggedIn"
        to="/"
        class="block"
        @click.native="closeMenu"
        >{{ $t('navigation.profile.invite') }}</NuxtLink
      >
    </nav>
    <div class="mb-10 lg:0">
      <p class="text-lg font-bold leading-loose mb-4">
        {{ $t('navigation.museum.header') }}
      </p>
      <NuxtLink to="/about" class="block mb-4" @click.native="closeMenu">{{
        $t('navigation.museum.about')
      }}</NuxtLink>
      <a href="#" class="block mb-4" @click.prevent="showTutorial">{{
        $t('navigation.museum.firstSteps')
      }}</a>
      <NuxtLink to="/faq" class="block" @click.native="closeMenu">{{
        $t('navigation.museum.faq')
      }}</NuxtLink>
    </div>
    <div class="mb-10 lg:mb-0">
      <div class="flex flex-row items-center mb-4">
        <div class="toggle flex flex-row overflow-hidden relative">
          <div
            class="toggle__item"
            @click="$router.push(switchLocalePath('de'))"
          >
            <input
              id="de"
              class="w-0 h-0 overflow-hidden absolute -top-10 -left-10"
              type="radio"
              value="de"
              name="language"
              :checked="$i18n.locale === 'de'"
            />
            <label
              for="de"
              class="px-3 inline-block leading-loose transform-gpu transition-colors duration-300"
              >DE</label
            >
          </div>
          <div
            class="toggle__item"
            @click="$router.push(switchLocalePath('en'))"
          >
            <input
              id="en"
              class="w-0 h-0 overflow-hidden absolute -top-10 -left-10"
              type="radio"
              value="en"
              name="language"
              :checked="$i18n.locale === 'en'"
            />
            <label
              for="en"
              class="px-3 inline-block leading-loose transform transition-colors duration-300"
              >EN</label
            >
          </div>
        </div>
        <p class="ml-4 leading-none">{{ $t('locale') }}</p>
      </div>
      <div class="flex flex-row items-center mb-4">
        <SimpleLanguageIcon class="w-6 h-6 mr-4" />
        <NuxtLink to="/simpleLanguage" @click.native="closeMenu">{{ $t('navigation.language.easyLanguage') }}</NuxtLink>
      </div>
      <div class="flex flex-row items-center mb-4">
        <SignLanguageIcon class="w-6 h-6 mr-4" />
        <NuxtLink to="/signLanguage" @click.native="closeMenu">{{ $t('navigation.language.signLanguage') }}</NuxtLink>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext, useStore } from '@nuxtjs/composition-api'
import LoginIcon from '@/assets/icons/login.svg?inline'
import LogoutIcon from '@/assets/icons/logout.svg?inline'
import SimpleLanguageIcon from '@/assets/icons/simpleLanguage.svg?inline'
import SignLanguageIcon from '@/assets/icons/signLanguage.svg?inline'

export default defineComponent({
  components: {
    LoginIcon,
    LogoutIcon,
    SimpleLanguageIcon,
    SignLanguageIcon,
  },
  setup(_, context) {
    const { $auth } = useContext()
    const store = useStore()

    function login() {
      $auth.login().then(closeMenu())
    }

    function logout() {
      $auth.logout().then(closeMenu())
    }

    function showProfileUpdate() {
      store.dispatch('showProfileUpdate')
      closeMenu()
    }

    function showTutorial() {
      store.dispatch('showTutorial')
      closeMenu()
    }

    function closeMenu() {
      context.emit('closeMenu')
    }

    return {
      login,
      logout,
      closeMenu,
      showProfileUpdate,
      showTutorial,
    }
  },
})
</script>
