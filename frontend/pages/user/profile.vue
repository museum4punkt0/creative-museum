<template>
  <div>
    <div w:flex="~ col 1">

      <UserProfileImage :user="user" w:m="r-8" />

      <div w:p="6" class="page-header">
        <button class="back-btn" @click.prevent="backButton">
          {{ $t('user.profile.self.headline', {firstName: user.firstName}) }}
        </button>
      </div>
      <div>
        {{ user.firstName }} {{ user.lastName }}
      </div>
      <div>
        {{ user.username }}
      </div>
      <div>
        <p>
          {{ user.description }}
        </p>
      </div>

      <NuxtLink to="/user/update" w:align="md:self-start" w:mt="6" class="btn-primary"> {{ $t('user.editProfile') }}</NuxtLink>

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
