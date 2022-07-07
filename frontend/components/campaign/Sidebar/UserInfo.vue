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
        {{ $t('user.editProfile') }}
      </button>
    </div>
    <div w:mb="10">
      <p w:font="bold" w:mb="3">
        {{ $t('user.score') }}
      </p>
      <UserScore :campaign="campaign" />
    </div>
    <div w:mb="10">
      <CampaignFilter />
    </div>
    <div w:mb="10">
      <PageFooter />
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext, computed } from '@nuxtjs/composition-api'
import UserScore from '../../user/UserScore.vue'
import CampaignFilter from '../CampaignFilter.vue'

export default defineComponent({
    props: {
        campaign: {
            type: Object,
            default: () => { }
        },
    },
    setup() {
        const context = useContext();
        const fullName = computed(() => {
            return context.$auth.user.firstName + " " + context.$auth.user.lastName;
        });
        return {
            fullName,
            campaignScore: computed(() => context.$auth.$storage.getState("campaignScore"))
        };
    },
    components: { UserScore, CampaignFilter }
})
</script>
