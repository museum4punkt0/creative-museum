<template>
  <div :style="styleAttr" class="flex flex-col flex-1 justify-between h-full">
    <template v-if="mode === 'detail'">
      <div class="page-header px-6 self-start">
        <a class="back-btn" @click.prevent="$emit('closeAwardDetail')">
          {{ $t('awards.detailHeadline') }}
        </a>
      </div>
      <div class="flex-1">
        <div class="box-shadow relative m-6">
          <AwardBadgeDetailText type="Award" :title="award.title" :text="award.description" :image="award.picture" :link="award.link" :price="award.price" :campaign="award.campaign"/>
        </div>
      </div>
      <div class="mx-6 mb-6">
        <button
          v-if="available && !award.taken"
          class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
          @click.prevent="preselectedUser !== '' ? giveAwayToPreselectedUser() :  mode = 'giveaway'"
        >
          {{ $t('awards.giftAward') }}
        </button>
        <button
          class="btn-outline w-full"
          @click.prevent="$emit('closeAwardDetail')"
        >
          {{ $t('close') }}
        </button>
      </div>
    </template>
    <template v-if="mode === 'giveaway'">
      <div class="page-header px-6 pb-0">
        <a class="back-btn" @click.prevent="mode = 'detail'">
          {{ $t('selectProfile') }}
        </a>
      </div>

      <div class="relative px-6 flex-auto">
        <input
          v-model="searchField"
          class="input-text my-6 pr-10"
          autocomplete="off"
        />
        <SearchIcon class="absolute right-9 top-9 w-7 h-7" />
        <template v-if="userList">
          <ul v-for="user in userList" :key="user.uuid">
            <li
              v-if="user.uuid !== $auth.user.uuid && !user.deleted"
              class="flex flex-row items-center my-2 award-item"
              :class="{ 'border rounded': user.uuid === selectedUser }"
              @click.prevent="select(user.uuid)"
            >
              <UserProfileImage :user="user" class="w-12 h-12 mr-4" />
              <div class="flex flex-col">
                <p>{{ user.fullName }}</p>
                <p class="text-$highlight text-sm">{{ $userName(user) }}</p>
              </div>
            </li>
          </ul>
        </template>
        <ul v-if="violations" class="text-sm text-red-500 my-6">
          <li v-for="(violation, key) in violations" :key="key">
            {{ $t('awards.violations.' + violation.code) }}
          </li>
        </ul>
      </div>

      <div class="mx-6 mb-6">
        <button
          v-if="selectedUser !== null"
          class="btn-outline w-full"
          @click.prevent="giveAway"
        >
          {{ $t('confirmSelection') }}
        </button>
      </div>
    </template>
    <template v-if="mode === 'giftComplete'">
      <div class="page-header px-6">
        <h2>{{ $t('awards.given') }}</h2>
      </div>
      <div class="box-shadow relative m-6">
        <AwardIcon v-if="award.picture" :image="award.picture" :title="award.title" class="h-40 w-auto mx-auto" />
        <p>
          {{
            $t('awards.givenConfirmText', {
              title: award.title,
              price: award.price.toLocaleString(),
              username: selectedUsername,
            })
          }}
        </p>
        <p class="text-xs text-$highlight">
          {{
            $t('awards.pointsLeft', {
              score: currentCampaignPoints[0].toLocaleString(),
            })
          }}
        </p>
      </div>

      <div class="mx-6 mb-6">
        <button class="btn-outline w-full" @click.prevent="resetView">
          {{ $t('close') }}
        </button>
      </div>
    </template>
  </div>
</template>
<script>
import {
  defineComponent,
  useContext,
  ref,
  computed,
  useStore
} from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'
import SearchIcon from '@/assets/icons/search.svg?inline'
import { userApi } from '@/api/user'
import { awardApi } from '~/api/award'

export default defineComponent({
  components: {
    SearchIcon
  },
  props: {
    award: {
      type: Object,
      required: true,
    },
    preselectedUser: {
      type: String,
      default: ''
    },
    available: {
      type: Boolean,
      default: false
    }
  },
  emits: ['closeAwardDetail'],
  setup(props, ctx) {
    const { searchUser } = userApi()
    const { awardUser } = awardApi()

    const context = useContext()
    const store = useStore()
    const mode = ref('detail')
    const userList = ref(null)
    const selectedUser = ref(null)
    const violations = ref(null)
    const currentCampaignPoints = computed(() => {
      return context.$auth.user.memberships
        .filter(function (membership) {
          if (membership.campaign.id === props.award.campaign.id) {
            return true
          } else {
            return false
          }
        })
        .map(function (item) {
          return item.score
        })
    })

    const bgColor = new TinyColor(props.award.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')
    const altfgColor = new TinyColor('#222329')

    const test1 = readability(bgColor, fgColor)
    const test2 = readability(bgColor, altfgColor)

    const campaignContrastColor = computed(() => {
      return (test1 < test2) ? '#222329' : '#FFFFFF'
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.award.campaign.color}; --highlight-contrast: ${campaignContrastColor.value};`
    })

    const debouncedSearchField = ref('')
    let timeout = null

    async function searchProfiles() {
      userList.value = await searchUser(debouncedSearchField.value)
    }

    function giveAwayToPreselectedUser() {
      giveAway()
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
      ctx.emit('closeAwardDetail')
    }

    const selectedUsername = computed(() => {
      for (const index in userList.value) {
        if (userList.value[index].uuid === selectedUser.value && !selectedUser.deleted) {
          return userList.value[index].username
        }
      }
      return ''
    })

    async function giveAway() {
      await awardUser(props.award.id, props.preselectedUser !== '' ? props.preselectedUser : selectedUser.value).then(function (
        response
      ) {
        if ('error' in response) {
          violations.value = response.error.response.data.violations
        } else {
          mode.value = 'giftComplete'
          store.dispatch('awardsChanged')
        }
      })
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
      },
    })

    return {
      backendURL: context.$config.backendURL,
      styleAttr,
      mode,
      searchField,
      debouncedSearchField,
      userList,
      select,
      selectedUser,
      giveAwayToPreselectedUser,
      giveAway,
      resetView,
      selectedUsername,
      currentCampaignPoints,
      violations,
    }
  },
})
</script>
