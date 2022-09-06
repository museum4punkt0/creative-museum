<template>
  <div :style="styleAttr">
    <div class="flex flex-row items-center mb-2 award-item cursor-pointer" @click.prevent="badgeDetailOpen = true">
      <div class="w-20 h-20 mr-3 overflow-hidden flex-shrink-0">
        <img
          v-if="badge.picture"
          :src="`${backendUrl}/${badge.picture.contentUrl}`"
          :alt="badge.title"
          class="max-w-18 h-auto"
        />
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
      <Modal v-if="badgeDetailOpen === true" @closeModal="badgeDetailOpen = false">
        <badgeDetail :badge="badge" @closebadgeDetail="badgeDetailOpen = false; $emit('awardsChange')" />
      </Modal>
    </transition>
  </div>
</template>
<script lang="ts">
import { defineComponent, useContext, ref, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    badge: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const context = useContext()
    const badgeDetailOpen = ref(false)

    const styleAttr = computed(() => {
      return `--highlight: ${props.badge.campaign.color}`
    })

    return {
      styleAttr,
      badgeDetailOpen,
      backendUrl: context.$config.backendUrl
    }
  }
})
</script>
>


</script>
