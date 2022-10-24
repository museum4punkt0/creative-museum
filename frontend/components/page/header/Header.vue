<template>
  <div>
    <div
      id="globalHeader"
      ref="globalHeader"
      class="relative container flex flex-row justify-between z-20 items-center"
    >
      <NuxtLink id="pageLogo" :to="localePath('/')" class="text-white/50 hover:text-$highlight focus:text-$highlight focus:outline-none">
        <Logo
          class="h-9 my-2 w-auto ml-5 transform-gpu transition-all duration-300 ease-in-out cursor-pointer"
          alt="Creative Museum Logo"
        />
      </NuxtLink>
      <button
        v-show="isAddButtonVisible"
        class="add-btn absolute left-1/2 -translate-x-1/2 block rounded-full border border-white h-6 w-6"
        :class="isAddVisible ? 'visible' : ''"
        @click.prevent="
          isAddVisible = !isAddVisible
          isMenuVisible = false
        "
      />
      <div class="flex flex-row mr-5 space-x-4 items-center">
        <PageHeaderUserInfo />
        <button
          class="h-6 w-6 transform-gpu hover:scale-125 transition-all duration-300 ease-in-out focus:outline-none group"
          type="button"
          :class="
            isMenuVisible
              ? '~ rounded-full white'
              : '~ rounded-none transparent'
          "
          @click.prevent="
            isMenuVisible = !isMenuVisible
            isAddVisible = false
          "
        >
          <span
            class="pointer-events-none space-y-1 block"
            :class="isMenuVisible ? '-mt-0.5' : ''"
          >
            <span
              class="block h-px bg-white transition-all duration-300 group-focus:bg-$highlight"
              :class="
                isMenuVisible
                  ? 'transform-gpu rotate-45 origin-center translate-y-1.5 scale-x-75'
                  : ''
              "
            />
            <span
              class="block h-px bg-white transform-gpu transition-all duration-500 group-focus:bg-$highlight"
              :class="
                isMenuVisible ? 'transform-gpu translate-x-10 opacity-0' : ''
              "
            />
            <span
              class="block h-px bg-white tranform-gpu transition-all duration-300 group-focus:bg-$highlight"
              :class="
                isMenuVisible
                  ? 'transform-gpu -rotate-45 origin-center -translate-y-1 scale-x-75'
                  : ''
              "
            />
          </span>
        </button>
      </div>
    </div>
    <div>
      <transition-group
        enter-active-class="duration-300 ease-out opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="duration-200 ease-in"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="isMenuVisible"
          key="0"
          class="absolute top-12 md:top-13 left-0 right-0 pt-10 md:pt-20 b-10 min-h-sm h-[calc(100vh-48px)] md:h-auto bg-grey shadow-lg shadow-black/20 z-50 md:max-h-full overflow-y-scroll scrollbar-hide"
        >
          <PageHeaderMainMenu @closeMenu="isMenuVisible = false" />
        </div>
        <div
          v-show="isAddVisible"
          key="1"
          class="absolute top-12 md:top-13 left-0 right-0 pt-10 md:pt-20 b-10 min-h-sm h-[calc(100vh-48px)] md:h-auto bg-grey shadow-lg shadow-black/20 z-50 md:max-h-full overflow-y-scroll scrollbar-hide"
        >
          <PostAdd @openAddModal="openAddModal" />
        </div>
      </transition-group>
      <transition
        enter-active-class="duration-300 ease-out opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="duration-200 ease-in"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <UtilitiesModal v-if="openAddModalType !== ''">
          <component
            :is="addComponentName"
            @abortPost="abortPost"
            @closeAddModal="closeAddModal"
          />
        </UtilitiesModal>
      </transition>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  useStore,
  computed,
  watch,
} from '@nuxtjs/composition-api'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  name: 'PageHeader',
  components: {
    Logo,
  },
  setup() {
    const store = useStore()

    const isAddVisible = ref(false)
    const isMenuVisible = ref(false)
    const openAddModalType = ref('')

    const addComponentName = computed(() => {
      return openAddModalType.value !== ''
        ? 'PostTypes' + openAddModalType.value + 'Add'
        : false
    })

    function openAddModal($type) {
      openAddModalType.value = $type
      isAddVisible.value = false
      store.dispatch('hideAddButton')
    }

    function abortPost() {
      openAddModalType.value = ''
      isAddVisible.value = true
      store.dispatch('showAddButton')
    }

    function closeAddModal() {
      store.dispatch('showAddButton')
      openAddModalType.value = ''
    }

    watch(
      () => store.getters.showAddModal,
      function () {
        isAddVisible.value = true
      }
    )

    watch(
      () => isAddVisible.value,
      function () {
        store.dispatch('hideAddModal')
      }
    )

    return {
      isAddButtonVisible: computed(() => store.state.showAddButton),
      isAddVisible,
      isMenuVisible,
      openAddModalType,
      addComponentName,
      openAddModal,
      closeAddModal,
      abortPost,
    }
  },
})
</script>
