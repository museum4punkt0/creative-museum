<template>
  <div>
    <h1 class="page-header mt-0 mb-1">{{ $t('user.search') }}</h1>
    <div class="relative">
      <input
        v-model="searchField"
        class="input-text my-6 pr-40"
        autocomplete="off"
      />
      <SearchIcon class="absolute right-4 top-3 w-7 h-7" />
    </div>
    <template v-if="userList">
      <ul v-for="user in userList" :key="user.uuid">
        <li v-if="user.uuid !== $auth.user.uuid && !user.deleted">
          <NuxtLink
            class="flex flex-row items-center my-2 award-item"
            :to="`/user/${user.uuid}`"
          >
            <UserProfileImage :user="user" class="w-12 h-12 mr-4" />
            <div class="flex flex-col">
              <p>{{ user.fullName }}</p>
              <p class="text-$highlight text-sm">@{{ user.username }}</p>
            </div>
          </NuxtLink>
        </li>
      </ul>
    </template>
  </div>
</template>
<script>
import { defineComponent, ref, computed } from '@nuxtjs/composition-api'
import SearchIcon from '@/assets/icons/search.svg?inline'
import { userApi } from '@/api/user'

export default defineComponent({
  components: {
    SearchIcon
  },
  setup() {
    const { searchUser } = userApi()
    const userList = ref(null)
    const debouncedSearchField = ref('')
    let timeout = null

    async function searchProfiles() {
      userList.value = await searchUser(debouncedSearchField.value)
    }

    const searchField = computed({
      get() {
        return debouncedSearchField.value
      },
      set(value) {
        if (timeout) clearTimeout(timeout)
        timeout = setTimeout(() => {
          debouncedSearchField.value = value
          if (value.length >= 3) {
            searchProfiles()
          } else {
            userList.value = null
          }
        }, 500)
      },
    })

    return {
      userList,
      searchField,
    }
  },
})
</script>
