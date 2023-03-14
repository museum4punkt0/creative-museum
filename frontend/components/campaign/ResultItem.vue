<template>
  <div>
    <NuxtLink
      :to="
        $auth.loggedIn && campaignResultItem.user.uuid === $auth.user.uuid
          ? localePath('/user/profile')
          : localePath(`/user/${campaignResultItem.user.uuid}`)
      "
      class="block mb-2"
      >{{ parentKey + 1 }}. {{ $userName(campaignResultItem.user) }}</NuxtLink
    >
    <div class="box-shadow-inset rounded-xl">
      <div
        class="bg-$highlight rounded-xl text-$highlight-contrast text-center"
        :style="`width: ${Math.round(
          (100 / rewardPoints) *
            campaignResultItem.rewardPoints
        )}%`"
      >
        <span
          class="px-3 py-0.5 inline-block whitespace-nowrap"
          :class="
            Math.round(
              (100 / rewardPoints) *
                campaignResultItem.rewardPoints
            ) < 30
              ? 'text-white'
              : ''
          "
          >{{ campaignResultItem.rewardPoints }}
          {{ campaignResultItem.rewardPoints === 1 ? 'Award' : 'Awards' }}</span
        >
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaignResultItem: {
      type: Object,
      required: true
    },
    rewardPoints: {
      type: Number,
      required: true
    },
    parentKey: {
      type: Number,
      required: true
    }
  },
})
</script>
