<template>
  <div>
    <div class="flex flex-row justify-between mb-10">
      <span class="text-2xl">{{ $t('campaign.awards') }}</span>
    </div>
    <div class="flex flex-row items-center mb-2">
      <div
        class="highlight-bg w-18 h-18 rounded-full mr-3 overflow-hidden flex-shrink-0"
      >
        <img
          src="https://fakeimg.pl/72/"
          alt="Dummy Image"
          class="max-w-18 h-auto"
        />
      </div>
      <div class="flex flex-col flex-grow">
        <p class="mb-1">Lorem Ipsum sit dolor</p>
        <p class="highlight-text text-sm">23.000 Punkte</p>
        <button class="btn-outline self-start mt-2" type="button">
          Verschenken
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {},
})
</script>
