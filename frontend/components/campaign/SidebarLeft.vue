<template>
  <div>
    <div w:mb="10">
      <div w:w="21" w:h="21" w:rounded="full" w:mb="4" class="highlight-bg">
        <img
          src="/images/placeholder_profile.png"
          w:w="21"
          w:h="21"
          w:object="contain center"
          w:rounded="full"
        />
      </div>
      <p w:text="2xl">
        {{ fullName }}
      </p>
      <p w:text="lg" w:mb="3" class="highlight-text">
        Lorem Ipsum
      </p>
      <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
        eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
        voluptua.
      </p>
      <button w:mt="10" w:py="2" w:w="full" class="btn-outline">
        Profil bearbeiten
      </button>
    </div>
    <div w:mb="10">
      <p w:font="bold" w:mb="3">
        Punktestand
      </p>
      <div
        w:px="4"
        w:py="2"
        w:rounded="full"
        w:flex="~ row"
        w:align="items-end"
        w:justify="center"
        class="box-shadow"
      >
        <span w:text="2xl" w:mr="2">{{campaignScore && campaignScore.value.score ? Math.abs(campaignScore.value.score) : '0' }}</span>
        <span>Punkte</span>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const context = useContext()

    const fullName = computed(() => {
      return context.$auth.user.firstName + ' ' + context.$auth.user.lastName
    })

    return {
      fullName,
      campaignScore: computed(() => context.$auth.$storage.getState('campaignScore'))
    }
  },
})
</script>
