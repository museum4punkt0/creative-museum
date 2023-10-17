<template>
  <div class="text-center text-white">
    {{ /* Headline */ }}
    <h2 class="sr-only">{{ $t('footer.title') }}</h2>

    {{ /* Logo */ }}
    <NuxtLink id="pageLogo" :to="localePath('/')" class="hover:(text-$highlight outline-none) focus:outline-none focus-visible:text-$highlight transform-gpu transition-all duration-300 ease-in-out logo">
      <LogoFull class="inline-block my-6 w-24" aria-label="Badisches Landesmuseum Logo" />
    </NuxtLink>

    {{ /* Copyright */ }}
    <div class="mb-3">
      <strong>&copy; {{ $dayjs().year() }} Badisches Landesmuseum</strong>
    </div>

    <ul>
      {{ /* Imprint */ }}
      <li>
        <NuxtLink to="/legal/imprint" class="block mb-2 focus:outline-none focus-visible:text-$highlight">{{
          $t('pages.imprint.title')
        }}</NuxtLink>
      </li>

      {{ /* Accessibility */ }}
      <li>
        <a
          :href="$t('pages.external.accessibility.url')"
          target="_blank"
          class="block mb-2 focus:outline-none focus-visible:text-$highlight"
          >{{ $t('pages.external.accessibility.title') }}</a
        >
      </li>

      {{ /* Privacy */ }}
      <li>
        <a href="https://www.landesmuseum.de/datenschutz" target="_blank" class="block mb-2 focus:outline-none focus-visible:text-$highlight">{{
          $t('pages.privacy.title')
        }}</a>
      </li>

      {{ /* Privacy settings */ }}
      <li>
        <a href="#" onClick="UC_UI.showSecondLayer();" class="block mb-2 focus:outline-none focus-visible:text-$highlight">{{
          $t('pages.privacySettings.title')
        }}</a>
      </li>

      {{ /* Terms of use */ }}
      <li>
        <a
          :href="$t('pages.external.termsOfUse.url')"
          target="_blank"
          class="block mb-2 focus:outline-none focus-visible:text-$highlight"
          >{{ $t('pages.external.termsOfUse.title') }}</a
        >
      </li>

      {{ /* State museum */ }}
      <li>
        <a
          :href="$t('pages.external.stateMuseum.url')"
          target="_blank"
          class="block mb-2 focus:outline-none focus-visible:text-$highlight"
          >{{ $t('pages.external.stateMuseum.title') }}</a
        >
      </li>

      {{ /* Instagram */ }}
      <li>
        <a
          :href="$t('pages.external.instagram.url')"
          target="_blank"
          class="block mb-2 focus:outline-none focus-visible:text-$highlight"
          >{{ $t('pages.external.instagram.title') }}</a
        >
      </li>

      {{ /* Twitter */ }}
      <li>
        <a
          :href="$t('pages.external.twitter.url')"
          target="_blank"
          class="block mb-2 focus:outline-none focus-visible:text-$highlight"
          >{{ $t('pages.external.twitter.title') }}</a
        >
      </li>

      {{ /* Facebook */ }}
      <li>
        <a
          :href="$t('pages.external.facebook.url')"
          target="_blank"
          class="block mb-2 focus:outline-none focus-visible:text-$highlight"
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
