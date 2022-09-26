<template>
  <div :style="styleAttr">
    <div
      class="flex flex-row items-center mb-2 award-item cursor-pointer"
      @click.prevent="badgeDetailOpen = true"
    >
      <div class="w-20 h-24 mr-3 overflow-hidden flex-shrink-0">
        <BadgeIcon v-if="badge.picture" :image="badge.picture" :title="badge.title" class="h-23 w-auto" />
      </div>
      <div class="flex flex-col flex-grow">
        <p class="mb-1">{{ badge.title }}</p>
        <p class="text-$highlight text-sm">{{ badge.description }}</p>
      </div>
    </div>
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <UtilitiesModal
        v-if="badgeDetailOpen === true"
        @closeModal="badgeDetailOpen = false"
      >
        <badgeDetail
          :badge="badge"
          @closebadgeDetail="
            badgeDetailOpen = false
            $emit('awardsChange')
          "
        />
      </UtilitiesModal>
    </transition>
  </div>
</template>
<script lang="ts">
import {
  defineComponent,
  ref,
  computed,
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    badge: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const badgeDetailOpen = ref(false)

    const styleAttr = computed(() => {
      return `--highlight: ${props.badge.campaign.color}`
    })

    return {
      styleAttr,
      badgeDetailOpen,
    }
  },
})
</script>
