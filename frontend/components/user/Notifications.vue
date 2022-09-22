<template>
  <div v-if="notifications && notifications.length > 0" class="mb-12">
    <div class="mb-10">
      <p class="text-2xl">
        {{ $t('campaign.latestPosts') }}
      </p>
    </div>

    <div
      v-for="(notificationGroup, key) in notificationsGrouped"
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
import { notificationApi } from '@/api/notification'

export default defineComponent({
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

    const today = computed(() => {
      return $dayjs().format('DD.MM.YYYY')
    })

    const notificationsGrouped = computed(() => {
      if (notifications.value) {
        const group = {}
        notifications.value.forEach((item) => {
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
      getNotifications,
    }
  },
})
</script>
