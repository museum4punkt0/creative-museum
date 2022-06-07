<template>
  <div>
    <div class="page-header">
      <NuxtLink to="/tutorial/step2" class="back-btn">Erste Schritte</NuxtLink>
    </div>
    <div class="box-shadow relative" w:text="center">
      <div w:text="left" w:m="b-10">
        <h2> {{ $t('tutorial.step3.title') }}</h2>
        <p>{{ $t('tutorial.step3.text') }}</p>
      </div>
      <div
       w:pos="absolute"
       w:left="6"
       w:bottom="6"
       w:text="color1 sm"
      >
        3/5
      </div>
    </div>
    <TutorialNavigation next="/tutorial/step4" prev="/tutorial/step2" />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'
export default defineComponent({
  layout: 'withoutFooter'
})
</script>