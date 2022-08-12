<template>
  <div w:text="center">
    <LogoFull w:display="inline-block" w:my="6" w:w="24" />
    <div w:mb="3">
      <strong>&copy; {{ $dayjs().year() }} Badisches Landesmuseum</strong>
    </div>
    <ul>
      <li>
        <NuxtLink to="/legal/imprint" w:display="block" w:mb="2">{{
          $t('pages.imprint.title')
        }}</NuxtLink>
      </li>
      <li>
        <NuxtLink to="/accessibility" w:display="block" w:mb="2">{{
          $t('pages.accessibility.title')
        }}</NuxtLink>
      </li>
      <li>
        <NuxtLink to="/legal/privacy" w:display="block" w:mb="2">{{
          $t('pages.privacy.title')
        }}</NuxtLink>
      </li>
      <li>
        <a
          :href="$t('pages.external.stateMuseum.url')"
          target="_blank"
          w:display="block"
          w:mb="2"
          >{{ $t('pages.external.stateMuseum.title') }}</a
        >
      </li>
      <li>
        <a
          :href="$t('pages.external.instagram.url')"
          target="_blank"
          w:display="block"
          w:mb="2"
          >{{ $t('pages.external.instagram.title') }}</a
        >
      </li>
      <li>
        <a
          :href="$t('pages.external.twitter.url')"
          target="_blank"
          w:display="block"
          w:mb="2"
          >{{ $t('pages.external.twitter.title') }}</a
        >
      </li>
      <li>
        <a
          :href="$t('pages.external.facebook.url')"
          target="_blank"
          w:display="block"
          w:mb="2"
          >{{ $t('pages.external.facebook.title') }}</a
        >
      </li>
    </ul>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'
import LogoFull from '@/assets/images/logo_full.svg?inline'

export default defineComponent({
  name: 'PageFooter',
  components: {
    LogoFull,
  },
})
</script>
