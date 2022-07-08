<template>
  <div>
    <div w:p="6" class="page-header">
      <NuxtLink :to="localePath('/')" class="back-btn">{{ $t('tutorial.firstSteps') }}</NuxtLink>
    </div>
    <div
      class="box-shadow-mobile"
      w:pos="relative"
      w:m="6 lg:0"
      w:p="6"
      w:text="center"
    >
      <div v-show="step === 1">
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
      <div v-show="step === 2">
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
      <div v-show="step === 3">
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
      <div v-show="step === 4">
        <div w:text="left" w:m="b-10">
          <h2> {{ $t('tutorial.step4.title') }}</h2>
          <p>{{ $t('tutorial.step4.text') }}</p>
        </div>
        <div
        w:pos="absolute"
        w:left="6"
        w:bottom="6"
        w:text="color1 sm"
        >
          4/5
        </div>
      </div>
      <div v-show="step === 5">
        <div w:text="left" w:m="b-10">
          <h2> {{ $t('tutorial.step5.title') }}</h2>
          <p>{{ $t('tutorial.step5.text') }}</p>
        </div>
        <div
        w:pos="absolute"
        w:left="6"
        w:bottom="6"
        w:text="color1 sm"
        >
          5/5
        </div>
      </div>
    </div>
    <div>
      <div
        w:p="6"
        w:m="t-10"
      >
        <button class="btn-primary" w:m="b-3"  @click.prevent="goNext()"> {{ $t('next') }}</button>
        <button v-if="step > 1" class="btn-outline" w:m="b-3" @click.prevent="goPrev()"> {{ $t('prev') }}</button>
        <button class="btn-outline" @click.prevent="finish()"> {{ $t('tutorial.skipTutorial') }}</button>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useStore, computed } from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  components: {
    Logo,
  },
  emits:['closeModal'],
  setup(props, context) {

    const step = ref(1)
    const store = useStore()
    const user = computed(() => store.state.auth.user);

    const { finishTutorial } = userApi()

    function goPrev() {
      step.value--
    }

    function goNext() {
      if (step.value > 4) {
        this.finish()
      } else {
        step.value++
      }
    }

    function finish() {
      finishTutorial(user.value.uuid)
      context.emit('closeModal')
    }

    return {
      step,
      goNext,
      goPrev,
      finish
    }
  },
})
</script>
