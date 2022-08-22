<template>
  <div class="text-center">
    <LogoFull class="inline-block my-6 w-24"/>
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
        <NuxtLink to="/accessibility" class="block mb-2">{{
          $t('pages.accessibility.title')
        }}</NuxtLink>
      </li>
      <li>
        <NuxtLink to="/legal/privacy" class="block mb-2">{{
          $t('pages.privacy.title')
        }}</NuxtLink>
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
import { defineComponent, ref } from '@nuxtjs/composition-api'
import LogoFull from '@/assets/images/logo_full.svg?inline'

export default defineComponent({
  name: 'PageFooter',
  components: {
    LogoFull,
  },
})
</script>
