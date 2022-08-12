<template>
  <div>
    <button
      w:px="2"
      w:pr="6"
      w:py="1"
      w:mb="3"
      w:rounded="full"
      w:align="self-start"
      w:text="sm"
      w:border="1 white"
      class="btn-outline btn-dropdown"
      :class="showOptions ? 'active' : ''"
      @click.prevent="toggleDropdown"
    >
      {{ label }}
    </button>
    <div
      v-show="showOptions"
      w:p="x-6 lg:l-0"
      w:w="screen lg:auto"
      w:ml="lg:2"
      w:flex="~ row lg:col"
      w:pos="absolute lg:static"
      w:left="0 lg:auto"
      w:top="8 lg:auto"
    >
      <button
        v-for="(item, key) in options"
        :key="key"
        w:px="2"
        w:py="1"
        w:mb="3"
        :w:ml="key > 0 ? '3 lg:0' : ''"
        w:rounded="full"
        w:text="sm space-nowrap"
        w:border="1 white"
        w:align="self-start"
        class="btn-outline border-dashed"
        :class="key === selectedValue ? 'active' : ''"
        @click="selectValue(key)"
      >
        {{ item.text }}
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
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
  },
  emits: ['dropdownChange', 'dropdownState'],
  setup(props, context) {
    const showOptions = ref(false)
    const selectedValue = ref(-1)

    function toggleDropdown() {
      showOptions.value = !showOptions.value
      context.emit('dropdownState', showOptions.value)
    }

    function selectValue(value) {
      if (selectedValue.value === value) {
        selectedValue.value = -1
      } else {
        selectedValue.value = value
      }
      context.emit('dropdownChange', value)
    }

    return {
      showOptions,
      selectedValue,
      toggleDropdown,
      selectValue,
    }
  },
})
</script>
