<template>
  <div>
    <div class="mb-10">
      <p class="text-2xl">
        {{ $t('campaign.latestPosts') }}
      </p>
    </div>
    <div class="mb-10">

      <div v-for="(notificationGroup, key) in notificationsGrouped" :key="key">
        <p class="text-lg">{{ today === key ? $t('today') : key }}</p>
        <NotificationItem v-for="notification in notificationGroup" :key="notification.id" :notification="notification" />
      </div>

    </div>
  </div>
</template>
<script>
import { defineComponent, ref, onMounted, useContext, computed } from '@nuxtjs/composition-api'
import { notificationApi } from '@/api/notification'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const { getNotifications } = notificationApi()
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


    onMounted(async () => {
      await fetchNotifications()
    })

    async function fetchNotifications() {
      notifications.value = await getNotifications(
        props.campaign ? props.campaign.id : null
      )
    }

    return {
      today,
      notifications,
      notificationsGrouped,
      fetchNotifications,
    }
  },
})
</script>
