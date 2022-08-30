<template>
  <div class="lg:grid lg:grid-cols-4 px-5 container text-white">
    <div class="mb-10 lg:mb-0">
      <client-only>
        <button
          v-if="!$auth.loggedIn"
          class="flex flex-row items-center font-bold leading-loose"
          @click.prevent="login"
          type="button"
        >
          <LoginIcon class="w-6 h-6 mr-2" />
          <span>Login</span>
        </button>
        <button
          v-else
          class="flex flex-row items-center font-bold leading-loose"
          @click.prevent="logout"
          type="button"
        >
          <LogoutIcon class="mr-2 w-auto" />
          <span>Logout</span>
        </button>
      </client-only>
    </div>
    <div class="mb-10 lg:mb-0">
      <p v-if="$auth.loggedIn" class="text-lg font-bold leading-loose mb-4">
        {{ $t('navigation.profile.header') }}
      </p>
      <NuxtLink
        v-if="$auth.loggedIn"
        to="/user/update"
        class="block mb-4"
        @click.native="closeMenu"
        >{{ $t('navigation.profile.settings') }}</NuxtLink
      >
      <NuxtLink v-if="$auth.loggedIn" to="/" class="block mb-4" @click.native="closeMenu">{{
        $t('navigation.profile.search')
      }}</NuxtLink>
      <NuxtLink v-if="$auth.loggedIn" to="/" class="block" @click.native="closeMenu">{{
        $t('navigation.profile.invite')
      }}</NuxtLink>
    </div>
    <div class="mb-10 lg:0">
      <p class="text-lg font-bold leading-loose mb-4">
        {{ $t('navigation.museum.header') }}
      </p>
      <NuxtLink to="/" class="block mb-4" @click.native="closeMenu">{{
        $t('navigation.museum.about')
      }}</NuxtLink>
      <NuxtLink to="/" class="block mb-4" @click.native="closeMenu">{{
        $t('navigation.museum.firstSteps')
      }}</NuxtLink>
      <NuxtLink to="/" class="block" @click.native="closeMenu">{{
        $t('navigation.museum.faq')
      }}</NuxtLink>
    </div>
    <div class="mb-10 lg:mb-0">
      <div class="flex flex-row items-center mb-4">
        <div class="toggle flex flex-row overflow-hidden">
          <div
            class="toggle__item"
            @click="$router.push(switchLocalePath('de'))"
          >
            <input
              id="de"
              class="w-0 h-0 overflow-hidden"
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
              class="w-0 h-0 overflow-hidden"
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
        <span>{{ $t('navigation.language.easyLanguage') }}</span>
      </div>
      <div class="flex flex-row items-center mb-4">
        <SignLanguageIcon class="w-6 h-6 mr-4" />
        <span>{{ $t('navigation.language.signLanguage') }}</span>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext } from '@nuxtjs/composition-api'
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

    function login() {
      $auth.login().then(closeMenu())
    }

    function logout() {
      $auth.logout().then(closeMenu())
    }

    function closeMenu() {
      context.emit('closeMenu')
    }

    return {
      login,
      logout,
      closeMenu,
    }
  },
})
</script>
