<template>
  <div :style="styleAttr" class="flex flex-col justify-between h-full">
    <template v-if="mode === 'detail'">
      <div class="page-header p-6">
        <a class="back-btn" @click.prevent="$emit('closeAwardDetail')">
          {{ $t('awards.detailHeadline') }}
        </a>
      </div>
      <div class="box-shadow-mobile relative m-6 lg:m-0 p-6">
        <img
          v-if="award.picture"
          :src="`${backendUrl}/${award.picture.contentUrl}`"
          :alt="award.title"
          class="w-full max-w-32 h-auto mx-auto"
        />
        <h1 class="text-2xl">{{ award.title }}</h1>
        <p>{{ award.description }}</p>
        <div class="text-$highlight">{{ award.price.toLocaleString() + ' ' + $t('points') }} </div>
      </div>

      <div class="mx-6 mb-6">
        <button
          class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
          @click.prevent="mode = 'giveaway'"
        >
          {{ $t('awards.giftAward') }}
        </button>
        <button class="btn-outline w-full" @click.prevent="$emit('closeAwardDetail')">{{ $t('close') }}</button>
      </div>
    </template>
    <template v-if="mode === 'giveaway'">
      <div class="page-header p-6">
        <a class="back-btn" @click.prevent="mode = 'detail'">
          {{ $t('selectProfile') }}
        </a>
      </div>

      <div class="relative p-6">
        <input type="text" class="input-text" v-model="searchField" />
        <template v-if="userList">
          <div v-for="user in userList" :key="user.uuid">
            <div
              class="flex flex-row items-center mb-2 award-item"
              :class="{'border rounded' : user.uuid === selectedUser}"
              @click.prevent="select(user.uuid)"
            >
              <div class="w-18 h-18 rounded-full mr-3 overflow-hidden flex-shrink-0">
                <img
                  v-if="user.profilePicture"
                  :src="`${backendUrl}/${user.profilePicture.contentUrl}`"
                  :alt="user.fullName"
                  class="max-w-18 h-auto"
                />
              </div>
              <div class="flex flex-col flex-grow">
                <p class="mb-1">{{ user.fullName }}</p>
              </div>
            </div>
          </div>
        </template>
      </div>

      <div class="mx-6 mb-6">
        <button v-if="selectedUser !== null" class="btn-outline w-full" @click.prevent="giveAway">
          {{ $t('confirmSelection') }}
        </button>
      </div>
    </template>
    <template v-if="mode === 'giftComplete'">

      <div class="page-header p-6">
        <h2>{{ $t('awards.given') }}</h2>
      </div>
      <div class="box-shadow-mobile relative m-6 lg:m-0 p-6">
        <p>
          {{ $t('awards.givenConfirmText', {title: award.title, price: award.price, username: selectedUsername}) }}
        </p>
      </div>

      <div class="mx-6 mb-6">
        <button
          @click.prevent="resetView"
          class="btn-outline w-full"
        >
          {{ $t('close') }}
        </button>
      </div>
    </template>
  </div>

</template>
<script>
import { defineComponent, useContext, ref, computed } from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'
import { userApi } from '@/api/user'
import { awardApi } from '~/api/award'

export default defineComponent({
  props: {
    award: {
      type: Object,
      required: true
    }
  },
  emits: ['closeAwardDetail'],
  setup(props) {
    const { searchUser } = userApi()
    const { awardUser } = awardApi()

    const context = useContext()
    const mode = ref('detail')
    const userList = ref(null)
    const selectedUser = ref(null)

    const bgColor = new TinyColor(props.award.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')

    const campaignContrastColor = computed(() => {
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.award.campaign.color}; --highlight-contrast: ${campaignContrastColor.value};`
    })

    const debouncedSearchField = ref('')
    let timeout = null

    async function searchProfiles() {
      userList.value = await searchUser(debouncedSearchField.value)
    }

    function select(userId) {
      if (selectedUser.value === userId) {
        selectedUser.value = null
        return
      }
      selectedUser.value = userId
    }

    function resetView() {
      selectedUser.value = null
      debouncedSearchField.value = ''
      mode.value = 'detail'
      context.emit('closeAwardDetail')

    }

    const selectedUsername = computed(() => {
      for (const index in userList.value) {
        if (userList.value[index].uuid === selectedUser.value) {
          return userList.value[index].username
        }
      }
      return ''
    })

    async function giveAway() {
      const result = await awardUser(props.award.id, selectedUser.value)
      mode.value = 'giftComplete'
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
          selectedUser.value = null
        }, 500)
      }
    })

    return {
      backendUrl: context.$config.backendUrl,
      styleAttr,
      mode,
      searchField,
      debouncedSearchField,
      userList,
      select,
      selectedUser,
      giveAway,
      resetView,
      selectedUsername
    }

  },
})
</script>
