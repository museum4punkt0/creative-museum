<template>
  <div>
    <div class="page-header">
      <NuxtLink to="/" class="back-btn">Erste Schritte</NuxtLink>
    </div>
    <div class="box-shadow relative" w:text="center">
      <Logo
        w:text="white/50"
        w:h="20"
        w:m="x-auto y-6"
      />
      <div w:text="left" w:m="b-10">
        <h2> {{ $t('tutorial.step1.title') }}</h2>
        <p>{{ $t('tutorial.step1.text') }}</p>
      </div>
      <div
       w:pos="absolute"
       w:left="6"
       w:bottom="6"
       w:text="color1 sm"
      >
        1/5
      </div>
    </div>
    <TutorialNavigation next="/tutorial/step2" />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'
import Logo from '@/assets/images/logo.svg?inline'
export default defineComponent({
  name: 'TutorialIndex',
  components: {
    Logo,
  },
  layout: 'withoutFooter'
})
</script>
