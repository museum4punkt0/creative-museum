<template>
  <div>
    <div class="page-header">
      <NuxtLink to="/campaigns" class="back-btn">Erste Schritte</NuxtLink>
    </div>
    <div class="box-shadow relative" w:text="center">
      <div w:text="left" w:m="b-10">
        <h2> {{ $t('tutorial.step2.title') }}</h2>
        <p>{{ $t('tutorial.step2.text') }}</p>
      </div>
      <div
       w:pos="absolute"
       w:left="6"
       w:bottom="6"
       w:text="color1 sm"
      >
        2/5
      </div>
    </div>
    <TutorialNavigation to="/tutorial/step3" prev="/tutorial" />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  layout: 'withoutFooter'
})
</script>