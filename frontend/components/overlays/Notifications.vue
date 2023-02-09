<template>
  <div v-if="notifications && notifications.length > 0" class="mb-12">
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <UtilitiesModal :aria-label=" $t('modal.notificationDetail')">
        <OverlaysNotificationItem
          :notification="notifications[0]"
          :notification-count="notifications.length"
          @refetchNotifications="getNotifications"
          @clearAllNotifications="clearAllNotifications"
        />
      </UtilitiesModal>
    </transition>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  useStore,
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
  setup() {
    const store = useStore()
    const { fetchUnviewedNotifications, updateNotificationAsViewed } = notificationApi()
    const notifications = ref(null)

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
      notifications.value = await fetchUnviewedNotifications()
      store.dispatch('updatedNotifications', notifications.value.length)
    }

    async function clearAllNotifications() {
      await notifications.value.forEach((item) => {
        updateNotificationAsViewed(item.id)
      })
      notifications.value = []
    }

    return {
      notifications,
      getNotifications,
      clearAllNotifications
    }
  },
})
</script>
