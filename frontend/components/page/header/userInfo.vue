<template>
  <div>
    <div
        v-if="user" w:flex="~ row" w:space="md:x-4" w:align="items-center"
      >
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
          class="highlight-bg"
          w:pos="absolute"
          w:top="1"
          w:p="y-0.5 x-2"
          w:m="-r-1"
          w:right="full"
          w:rounded="xl"
          w:text="xs black space-nowrap"
        >{{ user.score ? user.score : 0 }} P</span>
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
    </div>
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="user && !user.username"
        w:pos="fixed"
        w:bg="grey/60"
        w:top="0"
        w:left="0"
        w:bottom="0"
        w:right="0"
        w:z="100"
      >
        <div
          w:pos="fixed"
          w:top="1/2"
          w:left="1/2"
          w:transform="~ -translate-x-1/2 -translate-y-1/2"
          w:p="6"
          w:w="sm"
          w:shadow="lg"
          w:border="rounded-lg"
          w:bg="grey"
        >
          <h2 w:m="b-2"> {{ $t('provideUsername.title') }} </h2>
          <input v-model="username" type="text" class="input-text" />
          <button v-show="username.length > 3" class="btn-primary w-full mt-4" @click.prevent="submitUsername">{{ $t('submit') }}</button>
        </div>
      </div>
    </transition>
  </div>
</template>
<script>
import { useStore, useContext, computed, defineComponent, ref } from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  setup() {

    const { $auth } = useContext();
    const store = useStore()
    const user = computed(() => store.state.auth.user);
    const username = ref('')
    const { supplyUsername } = userApi()

    function submitUsername() {
      supplyUsername(user.value.uuid, username.value).then(
       function() {
         $auth.fetchUser()
       }
      )
    }

    return {
      user: computed(() => store.state.auth.user),
      username,
      submitUsername
    }
  },
})
</script>
