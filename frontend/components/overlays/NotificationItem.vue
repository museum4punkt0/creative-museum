<template>
  <div class="flex flex-col flex-1 h-full justify-between" :style="styleAttr">
    <div class="box-shadow mx-6 my-auto">
      <div
        class="bg-$highlight w-32 h-32 rounded-full mb-4 overflow-hidden flex-shrink-0 mx-auto"
      >
        <img
            v-if="notificationPicture"
            :src="`${backendUrl}/${notificationPicture}`"
            class="max-w-32 h-auto"
          />
      </div>
      <div class="flex flex-col flex-grow">
        <p v-if="notification.text"  class="mb-1">
          {{
            $t(`notifications.${notification.text}.title`, {
              award: notification.award ? notification.award.title : '',
              badge: notification.badge ? notification.badge.title : ''
            })
          }}
        </p>
        <p v-if="notification.text" class="text-$highlight text-sm">
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
      </div>
    </div>
    <button class="btn-outline m-6" @click.prevent="markNotificationAsViewed">{{ $t('close') }}</button>
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
