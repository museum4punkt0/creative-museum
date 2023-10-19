<template>
  <div
    v-if="notification"
    class="flex flex-col flex-1 justify-between"
    :style="styleAttr"
    @keyup.esc.stop="markNotificationAsViewed"
  >
    <div class="box-shadow mx-6 my-auto">

      <AwardIcon v-if="notification.award" :image="notification.award.picture" :title="notification.award.title" class="mx-auto h-40 w-auto" />
      <BadgeIcon v-else-if="notification.badge" :image="notification.badge.picture" :title="notification.badge.title" class="mx-auto h-40 w-auto" />
      <div
        v-else
        class="bg-$highlight w-32 h-32 rounded-full mb-4 overflow-hidden flex-shrink-0 mx-auto"
      >
      </div>
      <div class="flex flex-col flex-grow">
        <h3 v-if="notification.editorNotification">{{ notification.headline }}</h3>
        <p v-if="notification.text" class="mb-1">
          <template v-if="notification.editorNotification">
            {{ notification.text }}
          </template>
          <template v-else>
            {{
              $t(`notifications.${notification.text}.title`, {
                award: notification.award ? notification.award.title : '',
                badge: notification.badge ? notification.badge.title : '',
              })
            }}
          </template>
        </p>
        <p v-if="notification.text" class="text-$highlight text-sm">
          <template v-if="!notification.editorNotification">
            {{
              $t(`notifications.${notification.text}.text`, {
                campaign: notification.campaign
                  ? notification.campaign.title
                  : '',
                points: notification.scorePoints
                  ? notification.scorePoints.toLocaleString()
                  : '',
                author: notification.post
                  ? $userName(notification.post.author)
                  : '',
                badge: notification.badge ? notification.badge.title : '',
                award: notification.award ? notification.award.title : '',
                awardGiver: notification.award
                  ? $userName(notification.awardGiver)
                  : '',
                awardWinner: notification.award
                  ? $userName(notification.awardWinner)
                  : '',
              })
            }}
          </template>
        </p>
        <BadgeItem v-if="notification.badge" :badge="notification.badge" :link-only="true" />
        <AwardItem v-if="notification.award" :award="notification.award" :link-only="true" />
      </div>
    </div>
    <button id="notificationCloseButton" class="btn-outline mx-6 mt-6" :class="notificationCount === 1 ? 'mb-6 mb-safe' : ''" @click.prevent="markNotificationAsViewed">
      {{ $t('close') }}
    </button>
    <button v-if="notificationCount > 1" class="btn-highlight text-highlight-contrast m-6 mb-safe" @click.prevent="$emit('clearAllNotifications')">
      {{ $t('notifications.closeAll') }}
    </button>
  </div>
</template>
<script>
import { defineComponent, computed, useContext, onMounted } from '@nuxtjs/composition-api'
import { notificationApi } from '@/api/notification'

export default defineComponent({
  props: {
    notification: {
      type: Object,
      required: true,
    },
    notificationCount: {
      type: Number,
      required: true
    }
  },
  emits: [
    'clearAllNotifications'
  ],
  setup(props, context) {
    const { $config } = useContext()

    const styleAttr = computed(() => {
      return props.notification.campaign
        ? `--highlight: ${props.notification.campaign.color};`
        : ''
    })

    const { updateNotificationAsViewed } = notificationApi()

    const notificationPicture = computed(() => {
      const award = props.notification.award
      const badge = props.notification.badge
      if (badge || award) {
        if (badge && badge.picture) {
          return badge.picture.contentUrl
        } else if (award && award.picture) {
          return award.picture.contentUrl
        }
      }
    })

    onMounted(() => {
      if (process.client) {
        document.querySelector('#notificationCloseButton').focus()
      }
    })

    async function markNotificationAsViewed() {
      await updateNotificationAsViewed(props.notification.id)
      context.emit('refetchNotifications')
    }

    return {
      styleAttr,
      notificationPicture,
      backendURL: $config.backendURL,
      markNotificationAsViewed,
    }
  },
})
</script>
