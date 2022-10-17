<template>
  <div class="text-center text-white">
    <a href="https://www.landesmuseum.de/museum" target="_blank">
      <LogoFull class="inline-block my-6 w-24 text-white hover:text-$highlight transform-gpu transition-all duration-300 ease-in-out cursor-pointer" />
    </a>
    <div class="mb-3">
      <strong>&copy; {{ $dayjs().year() }} Badisches Landesmuseum</strong>
    </div>
    <ul>
      <li>
        <NuxtLink to="/legal/imprint" class="block mb-2">{{
          $t('pages.imprint.title')
        }}</NuxtLink>
      </li>
      <li>
        <a href="https://www.landesmuseum.de/erklaerung-zur-barrierefreiheit" target="_blank" class="block mb-2">{{
          $t('pages.accessibility.title')
        }}</a>
      </li>
      <li>
        <a href="https://www.landesmuseum.de/datenschutz" target="_blank" class="block mb-2">{{
          $t('pages.privacy.title')
        }}</a>
      </li>
      <li>
        <a href="#" onClick="UC_UI.showSecondLayer();" class="block mb-2">{{
          $t('pages.privacySettings.title')
        }}</a>
      </li>
      <li>
        <a
          :href="$t('pages.external.stateMuseum.url')"
          target="_blank"
          class="block mb-2"
          >{{ $t('pages.external.stateMuseum.title') }}</a
        >
      </li>
      <li>
        <a
          :href="$t('pages.external.instagram.url')"
          target="_blank"
          class="block mb-2"
          >{{ $t('pages.external.instagram.title') }}</a
        >
      </li>
      <li>
        <a
          :href="$t('pages.external.twitter.url')"
          target="_blank"
          class="block mb-2"
          >{{ $t('pages.external.twitter.title') }}</a
        >
      </li>
      <li>
        <a
          :href="$t('pages.external.facebook.url')"
          target="_blank"
          class="block mb-2"
          >{{ $t('pages.external.facebook.title') }}</a
        >
      </li>
    </ul>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'
import LogoFull from '@/assets/images/logo_full.svg?inline'

export default defineComponent({
  name: 'PageFooter',
  components: {
    LogoFull,
  },
})
</script>
