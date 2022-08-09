<template>
  <div>
    <div
      id="globalHeader"
      ref="globalHeader"
      w:pos="relative"
      w:container="~"
      w:flex="~ row"
      w:justify="between"
      w:z="100"
      w:align="items-center"
    >
      <NuxtLink id="pageLogo" :to="localePath('/')">
        <Logo
          w:text="white/50 hover:$highlight"
          w:h="8 md:12"
          w:m="l-5 y-3"
          w:transition="all duration-300 ease-in-out"
          w:transform="gpu hover:scale-125"
          w:cursor="pointer"
        />
      </NuxtLink>
      <button
        v-show="isAddButtonVisible"
        class="add-btn"
        :class="isAddVisible ? 'visible' : ''"
        w:pos="absolute"
        w:left="1/2"
        w:transform="-translate-x-1/2"
        w:display="block"
        w:border="~ rounded-full white"
        w:h="6"
        w:w="6"
        @click.prevent="
          isAddVisible = !isAddVisible
          isMenuVisible = false
        "
      />
      <div w:flex="~ row" w:m="r-5" w:space="x-4" w:align="items-center">
        <PageHeaderUserInfo />
        <button
          w:transition="scale duration-300 ease-in-out"
          w:transform="gpu hover:scale-125"
          w:h="6"
          w:w="6"
          :w:border="
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
            w:pointer-events="none"
            w:space="y-1"
            w:display="block"
            :w:m="isMenuVisible ? '-t-0.5' : ''"
          >
            <span
              w:display="block"
              w:h="px"
              w:bg="white"
              w:transition="all duration-300"
              :w:transform="
                isMenuVisible
                  ? 'gpu rotate-45 origin-center translate-y-1.5 scale-x-75'
                  : ''
              "
            />
            <span
              w:display="block"
              w:h="px"
              w:bg="white"
              w:transition="all duration-500"
              :w:transform="isMenuVisible ? 'gpu translate-x-10 opacity-0' : ''"
            />
            <span
              w:display="block"
              w:h="px"
              w:bg="white"
              w:transition="all duration-300"
              :w:transform="
                isMenuVisible
                  ? 'gpu -rotate-45 origin-center -translate-y-1 scale-x-75'
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
          w:pos="absolute"
          w:top="12 md:16"
          w:left="0"
          w:right="0"
          w:p="t-10 md:t-20 b-10"
          w:min-h="sm"
          w:bg="grey"
          w:shadow="lg black/20"
          w:z="50"
        >
          <PageHeaderMainMenu @closeMenu="isMenuVisible = false" />
        </div>
        <div
          v-show="isAddVisible"
          key="1"
          w:pos="absolute"
          w:top="12 md:16"
          w:left="0"
          w:right="0"
          w:p="t-10 md:t-20 b-10"
          w:min-h="sm"
          w:bg="grey"
          w:shadow="lg black/20"
          w:z="50"
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
        <Modal v-if="openAddModalType !== ''">
          <component :is="addComponentName" @abortPost="abortPost" @closeAddModal="closeAddModal" />
        </Modal>
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
