<template>
  <div>
    <div w:flex="~ col 1">


      <div w:p="6" class="page-header">
        <button class="back-btn" @click.prevent="backButton">
          {{ $t('user.profile.self.headline', {firstName: user.firstName}) }}
        </button>
      </div>

      <img
        v-if="'profilePicture' in user"
        :src="'https://backend.creative-museum.ddev.site' + user.profilePicture.contentUrl"
        w:w="32"
        w:h="32"
        w:mb="12"
        w:rounded="full"
      />

      <h1 w:text="2xl">{{ user.firstName }} {{ user.lastName }}</h1>
      <p class="highlight-text" w:mb="2">@{{ user.username }}</p>
      <p>
        {{ user.description }}
      </p>

      <NuxtLink to="/user/update" w:align="md:self-start" w:mt="10" class="btn-primary btn-outline"> {{ $t('user.editProfile') }}</NuxtLink>

    </div>
  </div>
</template>


<script>

import { defineComponent, useAsync, useRoute, useRouter, computed, useContext, ref, useStore } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import { userApi } from '@/api/user'

export default defineComponent({
  name: 'ProfilePage',
  setup(props) {

    const store = useStore()
    const user = computed(() => store.state.auth.user)

    function backButton() {

    }

    return {
      user,
      backButton
    }
  }
})
</script>
