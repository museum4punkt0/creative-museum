<template>
  <div>
    <button
      class="btn-outline btn-dropdown px-2 pr-6 py-1 mb-3 rounded-full self-start text-sm border border-white"
      :class="showOptions ? 'active' : ''"
      @click.prevent="toggleDropdown"
    >
      {{ label }}
    </button>
    <div
      v-show="showOptions"
      class="-ml-6 pr-6 lg:pl-0 w-screen lg:w-auto lg:ml-2 flex flex-row lg:flex-col absolute lg:static left-0 lg:left-auto top-8 lg:top-auto overflow-x-scroll scrollbar-hide"
    >
      <button
        v-for="(item, key) in options"
        :key="key"
        class="btn-outline border-dashed px-2 py-1 mb-3 ml-3 lg:ml-0 rounded-full text-sm whitespace-nowrap border border-white self-start"
        :class="[
          item.id === selectedValue ? 'active' : '',
          key > 0 ? 'ml-3' : 'ml-6',
        ]"
        @click="selectValue(item.id)"
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
  setup(_, context) {
    const showOptions = ref(false)
    const selectedValue = ref(-1)

    function toggleDropdown() {
      showOptions.value = !showOptions.value
      context.emit('dropdownState', showOptions.value)
    }

    function closeDropdown() {
      showOptions.value = false
      selectedValue.value = -1
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
      closeDropdown,
    }
  },
})
</script>
