<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header px-6">
      <button class="back-btn" @click.prevent="$emit('closeModal')">{{
        $t('tutorial.firstSteps')
      }}</button>
    </div>
    <div class="box-shadow-mobile relative m-6 lg:m-0 p-6 text-center">
      {{ /* Step 1 */ }}
      <div v-show="step === 1">
        <div class="h-30">
          <Logo class="text-white/50 h-20 mx-auto my-6" />
        </div>
        <div class="text-left mb-10">
          <h2>{{ $t('tutorial.step1.title') }}</h2>
          <p>{{ $t('tutorial.step1.text') }}</p>
        </div>
        <div class="absolute left-6 bottom-6 text-color1 text-sm">1/5</div>
      </div>

      {{ /* Step 2 */ }}
      <div v-show="step === 2">
        <div class="h-30">
          <div class="lg:inline-block max-w-full">
            <div
              class="box-shadow px-4 py-2 rounded-none flex flex-row items-end justify-center"
            >
              <img src="/images/tutorialCreate.png" class="mx-auto" height="45" width="259" />
            </div>
          </div>
        </div>
        <div class="text-left mb-10">
          <h2>{{ $t('tutorial.step2.title') }}</h2>
          <p>{{ $t('tutorial.step2.text') }}</p>
        </div>
        <div class="absolute left-6 bottom-6 text-color1 text-sm">2/5</div>
      </div>

      {{ /* Step 3 */ }}
      <div v-show="step === 3">
        <div class="h-30">
          <div class="lg:max-w-1/2 mx-auto">
            <div
              class="box-shadow px-4 py-2 rounded-full flex flex-row items-end justify-center"
            >
              <span class="text-2xl mr-2">{{
                Math.abs(10000).toLocaleString()
              }}</span>
              <span>{{ $t('points') }}</span>
            </div>
          </div>
        </div>
        <div class="text-left mb-10">
          <h2>{{ $t('tutorial.step3.title') }}</h2>
          <p>{{ $t('tutorial.step3.text') }}</p>
        </div>
        <div class="absolute left-6 bottom-6 text-color1 text-sm">3/5</div>
      </div>

      {{ /* Step 4 */ }}
      <div v-show="step === 4">
        <div class="h-30">
          <img src="/images/tutorialAward.svg" class="mx-auto my-6 max-h-30" />
        </div>
        <div class="text-left mb-10">
          <h2>{{ $t('tutorial.step4.title') }}</h2>
          <p>{{ $t('tutorial.step4.text') }}</p>
        </div>
        <div class="absolute left-6 bottom-6 text-color1 text-sm">4/5</div>
      </div>

      {{ /* Step 5 */ }}
      <div v-show="step === 5">
        <div>
          <img src="/images/tutorialBadge.png" class="mx-auto mt-[.563rem] mb-[.5rem] max-h-[7.938rem]" />
        </div>
        <div class="text-left mb-10">
          <h2>{{ $t('tutorial.step5.title') }}</h2>
          <p>{{ $t('tutorial.step5.text') }}</p>
        </div>
        <div class="absolute left-6 bottom-6 text-color1 text-sm">5/5</div>
      </div>

    </div>
    <div>
      <div class="p-6 mt-10">
        <button class="btn-primary block w-full mb-3" @click.prevent="goNext()">
          {{ $t('next') }}
        </button>
        <button
          v-if="step > 1"
          class="btn-outline hover:bg-color1 hover:border-color1 block w-full py-2 mb-3"
          @click.prevent="goPrev()"
        >
          {{ $t('prev') }}
        </button>
        <button
          class="btn-outline hover:bg-color1 hover:border-color1 block w-full py-2"
          @click.prevent="finish()"
        >
          {{ $t('tutorial.skipTutorial') }}
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext } from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  components: {
    Logo
  },
  emits: ['closeModal'],
  setup(_, context) {
    const step = ref(1)
    const { $auth } = useContext()

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
      finishTutorial($auth.user.uuid)
      context.emit('closeModal')
    }

    return {
      step,
      goNext,
      goPrev,
      finish,
    }
  },
})
</script>
