<template>
  <div>
    <div
      w:flex="~ row"
      w:justify="between"
      w:mb="10"
    >
      <span w:text="2xl">{{ $t('campaign.awards') }}</span>
    </div>
    <div
      w:flex="~ row"
      w:align="items-center"
      w:mt="2"
    >
      <div
      w:w="18"
      w:h="18"
      w:rounded="full"
      w:mr="3"
      w:overflow="hidden"
      w:flex="shrink-0"
      class="highlight-bg"
      >
        <img src="https://fakeimg.pl/72/" alt="Dummy Image" w:max-w="18" w:h="auto" />
      </div>
      <div
        w:flex="~ col grow"
      >
        <p w:mb="1">Lorem Ipsum sit dolor</p>
        <p w:text="sm" class="highlight-text">23.000 Punkte</p>
        <button
          w:mt="2"
          w:align="self-start"
          class="btn-outline"
        >
          Verschenken
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {

  },
})
</script>
