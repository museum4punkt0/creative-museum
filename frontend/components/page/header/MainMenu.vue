<template>
  <div w:p="x-5" w:grid="lg:~ lg:cols-4" w:container="~">
    <div w:mb="10 lg:0">
      <button
        v-if="!$auth.loggedIn"
        w:text="white" w:flex="~ row" w:align="items-center" w:font="bold leading-loose"
        @click.prevent="login">
        <LoginIcon />
        <span>Login</span>
      </button>
      <button
        v-else
        w:text="white" w:flex="~ row" w:align="items-center" w:font="bold leading-loose"
        @click.prevent="logout">
        <LogoutIcon w:m="r-2" />
        <span>Logout</span>
      </button>
    </div>
    <div w:mb="10 lg:0">
      <p w:text="lg" w:font="bold leading-loose" w:mb="4">{{ $t('navigation.profile.header') }}</p>
      <NuxtLink to="/" w:display="block" w:mb="4">{{ $t('navigation.profile.settings') }}</NuxtLink>
      <NuxtLink to="/" w:display="block" w:mb="4">{{ $t('navigation.profile.search') }}</NuxtLink>
      <NuxtLink to="/" w:display="block">{{ $t('navigation.profile.invite') }}</NuxtLink>
    </div>
    <div w:mb="10 lg:0">
      <p w:text="lg" w:font="bold leading-loose" w:mb="4">{{ $t('navigation.museum.header') }}</p>
      <NuxtLink to="/" w:display="block" w:mb="4">{{ $t('navigation.museum.about') }}</NuxtLink>
      <NuxtLink to="/" w:display="block" w:mb="4">{{ $t('navigation.museum.firstSteps') }}</NuxtLink>
      <NuxtLink to="/" w:display="block">{{ $t('navigation.museum.faq') }}</NuxtLink>
    </div>
    <div w:mb="10 lg:0">
      <div w:flex="~ row" w:align="items-center" w:mb="4">
        <div class="toggle" w:flex="~ row" w:overflow="hidden">
          <div class="toggle__item" @click="$router.push(switchLocalePath('de'))">
            <input id="de" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="de" name="language" :checked="$i18n.locale === 'de'">
            <label for="de" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">DE</label>
          </div>
          <div class="toggle__item" @click="$router.push(switchLocalePath('en'))">
            <input id="en" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="en" name="language" :checked="$i18n.locale === 'en'">
            <label for="en" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">EN</label>
          </div>
        </div>
        <p w:ml="4" w:font="leading-none">{{ $t('locale') }}</p>
      </div>
      <div w:flex="~ row" w:align="items-center" w:mb="4">
        <SimpleLanguageIcon />
        <span>{{ $t('navigation.language.easyLanguage') }}</span>
      </div>
      <div w:flex="~ row" w:align="items-center" w:mb="4">
        <SignLanguageIcon />
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
    SignLanguageIcon
  },
  setup() {

    const { $auth } = useContext()

    function login() {
      $auth.login().then(closeMenu())
    }

    function logout() {
      $auth.logout().then(closeMenu())
    }

    return {
      login,
      logout
    }
  },
})
</script>
