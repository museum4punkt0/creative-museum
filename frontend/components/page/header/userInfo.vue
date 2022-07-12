<template>
  <div>
    <div
        v-if="user" w:flex="~ row" w:space="md:x-4" w:align="items-center"
      >
      <ClientOnly>
        <div
          w:position="relative"
        >
            <img
              src="/images/placeholder_profile.png"
              w:w="6"
              w:h="6"
              w:object="contain center"
              w:rounded="full"
            />
            <span
              class="highlight-bg"
              w:pos="absolute"
              w:h="2"
              w:w="2"
              w:top="0"
              w:right="0"
              w:rounded="full"
              w:white
            />
            <span
              v-if="campaignScore && campaignScore.value"
              class="highlight-bg"
              w:pos="absolute"
              w:top="1"
              w:p="y-0.5 x-2"
              w:m="-r-1"
              w:right="full"
              w:rounded="xl"
              w:text="xs black space-nowrap"
            >{{ campaignScore.value.score.toLocaleString() }} P</span>
        </div>
        <span
          w:text="sm overflow-ellipsis ..."
          w:display="hidden md:inline-block"
          w:overflow="hidden"
          w:min-w="24"
          w:max-w="32"
        >
          {{ user.username ? `@${user.username}` : username ? `@${username}` : $t('noUsername') }}
        </span>
      </ClientOnly>
    </div>
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >

      <Modal v-if="user && !user.username" t="10">
        <div>
          <h1 class="page-header" w:p="4"> {{ $t('provideUsername.title') }} </h1>
          <div w:p="x-4 b-4">
            <input v-model="username" type="text" class="input-text" placeholder="Username*" w:p="4" />
            <div w:pos="fixed lg:static" w:bottom="0" w:left="0" w:right="0" w:p="4 lg:0">
              <button v-show="username.length > 3" class="btn-primary w-full mt-4" @click.prevent="submitUsername">{{ $t('submit') }}</button>
            </div>
          </div>
        </div>
      </Modal>
    </transition>
  </div>
</template>
<script>
import { useStore, useContext, computed, defineComponent, ref } from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  setup() {

    const context = useContext();
    const store = useStore()
    const username = ref('')
    const { supplyUsername } = userApi()

    function submitUsername() {
      supplyUsername(username.value).then(
       function() {
         $auth.fetchUser()
       }
      )
    }

    return {
      user: computed(() => store.state.auth.user),
      campaignScore: computed(() => context.$auth.$storage.getState('campaignScore')),
      username,
      submitUsername
    }
  },
})
</script>
