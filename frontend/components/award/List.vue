<template>
  <div v-if="availableAwards.length || unavailableAwards.length">
    <div class="flex flex-row justify-between mb-10">
      <span class="text-2xl">{{ $t('campaign.awards') }}</span>
    </div>
    <div v-if="availableAwards.length" class="mb-6">
      <div class="text-$highlight text-sm mb-2">
        {{ $t('awards.available') }}
      </div>
      <AwardItem
        v-for="(award, key) in availableAwards"
        :key="key"
        :award="award"
        @awardsChange="fetchAllAwards"
      />
    </div>
    <div v-if="unavailableAwards.length" class="mb-6">
      <div class="text-$highlight text-sm mb-2">
        {{ $t('awards.unavailable') }}
      </div>
      <AwardItem
        v-for="(award, key) in unavailableAwards"
        :key="key"
        :award="award"
      />
    </div>
    <div v-if="giftedAwards.length" class="mb-6">
      <div class="text-$highlight text-sm mb-2">
        {{ $t('awards.gifted') }}
      </div>
      <AwardItem
        v-for="(award, key) in giftedAwards"
        :key="key"
        :award="award"
      />
    </div>
    <div v-if="receivedAwards.length" class="mb-6">
      <div class="text-$highlight text-sm mb-2">
        {{ $t('awards.received') }}
      </div>
      <AwardItem
        v-for="(award, key) in receivedAwards"
        :key="key"
        :award="award.award"
      />
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, useContext, computed, onMounted, ref } from '@nuxtjs/composition-api'
import { awardApi } from '@/api/award'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {}
    }
  },
  setup(props) {

    const { fetchAwards, fetchAwarded} = awardApi()
    const { $auth } = useContext()

    const availableAwards = ref(<any>[])
    const unavailableAwards = ref(<any>[])
    const giftedAwards = ref(<any>[])
    const receivedAwards = ref(<any>[])

    onMounted(async () => {
      await fetchAllAwards()
    })

     async function fetchAllAwards() {

      if (!$auth.loggedIn) {
        return
      }

      availableAwards.value = []
      unavailableAwards.value = []
      giftedAwards.value = []
      receivedAwards.value = []

      await fetchAwards(
        props.campaign ? props.campaign.id : null
      ).then(function(response) {
        if ($auth.loggedIn) {
          response.forEach((item:any) => {
            if (item.available && !item.taken) {
              availableAwards.value.push(item)
            } else if ( item.taken ) {
              giftedAwards.value.push(item)
            } else {
              unavailableAwards.value.push(item)
            }
          })
        }
      })
      receivedAwards.value = await fetchAwarded()
    }

    return {
      availableAwards,
      unavailableAwards,
      giftedAwards,
      receivedAwards,
      fetchAllAwards
    }

  }
})
</script>
