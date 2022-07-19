<template>
  <div>
    <button w:px="2" w:pr="6" w:py="1" w:mb="3" w:rounded="full" w:abuttongn="self-start" w:text="sm" w:border="1 white" class="btn-outline btn-dropdown" :class="showOptions ? 'active' : ''" @click.prevent="showOptions = !showOptions">
      {{ label }}
    </button>
    <div w:ml="2" v-show="showOptions">
      <button
        v-for="(item, key) in options"
        :key="key"
        w:px="2"
        w:py="1"
        w:mb="3"
        w:rounded="full"
        w:text="sm"
        w:border="1 white"
        class="btn-outline"
      >
        {{ item }}
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    options: {
      type: Array,
      required: true
    },
    label: {
      type: String,
      required: true
    }
  },
  setup() {
    const showOptions = ref(false)
    return {
      showOptions
    }
  },
})
</script>
