<template>
  <div :style="styleAttr">
    <div
      class="bg-$highlight w-10 h-10 rounded-full mb-4 mr-3 overflow-hidden flex-shrink-0"
    >
      <img
          v-if="notificationPicture"
          :src="`${backendUrl}/${notificationPicture}`"
          class="max-w-18 h-auto"
        />
    </div>
    <div class="flex flex-col flex-grow">
      <p class="mb-1">
        {{
          $t(`notifications.${notification.text}.title`, {
            award: notification.award ? notification.award.title : '',
            badge: notification.badge ? notification.badge.title : ''
          })
        }}
      </p>
      <p class="text-$highlight text-sm">
        {{
          $t(`notifications.${notification.text}.text`, {
            campaign: notification.campaign.title,
            points: notification.scorePoints ? notification.scorePoints.toLocaleString() : '',
            author: notification.post ? notification.post.author.username : '',
            badge: notification.badge ? notification.badge.title : '',
            award: notification.award ? notification.award.title : ''
          })
        }}
      </p>
      <button @click.prevent="markNotificationAsViewed">Close</button>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed, useContext } from '@nuxtjs/composition-api'
import { notificationApi } from '@/api/notification'

export default defineComponent({
  props: {
    notification: {
      type: Object,
      required: true
    }
  },
  setup(props, context) {

    const { $config } = useContext()

    const styleAttr = computed(() => {
      return `--highlight: ${props.notification.campaign.color};`
    })

    const { updateNotificationAsViewed } = notificationApi()

    const notificationPicture = computed(() => {
      const award = props.notification.award
      const badge = props.notification.badge
      if (badge || award) {
        if (badge.picture) {
          return badge.picture.contentUrl
        } else if (award.picture) {
          return award.picture.contentUrl
        }
      }
    })

    async function markNotificationAsViewed() {
      await updateNotificationAsViewed(props.notification.id)
      context.emit('refetchNotifications')
    }

    return {
      styleAttr,
      notificationPicture,
      backendUrl: $config.backendUrl,
      markNotificationAsViewed
    }
  },
})
</script>
