<template>
  <div v-if="notifications && notifications.length > 0" class="mb-12">
    <div class="flex flex-row justify-between mb-10">
      <h2 class="text-2xl">{{ $t('campaign.latestPosts') }}</h2>
      <button
        v-if="
          (notifications.length > 3)
        "
        class="highlight-text text-sm flex flex-row items-center leading-none cursor-pointer whitespace-nowrap border border-transparent rounded-xl p-1 -mt-1 -mb-1 focus-visible:border focus-visible:border-white"
        :aria-label="$t('notifications.showAll')"
        @click.prevent="showAllNotifications = !showAllNotifications"
      >
        <ArrowIcon
          class="relative w-2 top-0 mr-0.5 inline-block transition-all duration-200"
          :class="showAllNotifications ? 'transform-gpu rotate-180' : ''"
        />
        <span v-if="!showAllNotifications">{{ $t('showAll') }}</span>
        <span v-else>{{ $t('hide') }}</span>
      </button>
    </div>
    <div v-if="!showAllNotifications">
      <div
        v-for="(notificationGroup, key) in notificationsGrouped"
        :key="key"
        class="mb-10"
      >
        <h3 class="text-lg">{{ today === key ? $t('today') : key }}</h3>
        <ul>
          <li v-for="notification in notificationGroup" :key="notification.id">
            <NotificationItem
              :notification="notification"
            />
          </li>
        </ul>
      </div>
    </div>
    <div v-else>
      <div
        v-for="(notificationGroup, key) in notificationsGroupedAll"
        :key="key"
        class="mb-10"
      >
        <p class="text-lg">{{ today === key ? $t('today') : key }}</p>
        <NotificationItem
          v-for="notification in notificationGroup"
          :key="notification.id"
          :notification="notification"
        />
      </div>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  useContext,
  useStore,
  computed,
  watch,
} from '@nuxtjs/composition-api'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'
import { notificationApi } from '@/api/notification'

export default defineComponent({
  components: {
    ArrowIcon
  },
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const store = useStore()
    const { fetchNotifications } = notificationApi()
    const notifications = ref(null)
    const { $dayjs } = useContext()
    const showAllNotifications = ref(false)

    const today = computed(() => {
      return $dayjs().format('DD.MM.YYYY')
    })

    const notificationsGrouped = computed(() => {
      if (notifications.value) {
        const group = {}
        notifications.value.forEach((item, key) => {
          const day = $dayjs(item.created).format('DD.MM.YYYY')
          if (key < 3) {
            if (group[day]) {
              group[day].push(item)
            } else {
              group[day] = []
              group[day].push(item)
            }
          }
        })
        return group
      }
    })

    const notificationsGroupedAll = computed(() => {
      if (notifications.value) {
        const group = {}
        notifications.value.forEach((item, key) => {
          const day = $dayjs(item.created).format('DD.MM.YYYY')
            if (group[day]) {
              group[day].push(item)
            } else {
              group[day] = []
              group[day].push(item)
            }
        })
        return group
      }
    })

    watch(
      () => store.getters.notificationsUpdated,
      async function (newVal) {
        if (newVal === false) {
          await getNotifications()
        }
      }
    )

    onMounted(async () => {
      await getNotifications()
    })

    async function getNotifications() {
      notifications.value = await fetchNotifications(
        props.campaign ? props.campaign.id : null
      )
      store.dispatch('updatedNotifications')
    }

    return {
      today,
      notifications,
      notificationsGrouped,
      notificationsGroupedAll,
      showAllNotifications,
      getNotifications,
    }
  },
})
</script>
