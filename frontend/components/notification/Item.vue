<template>
  <div class="flex flex-row mt-2" :style="styleAttr">
    <div
      class="bg-$highlight w-10 h-10 rounded-full mb-4 mr-3 overflow-hidden flex-shrink-0"
    >
      <img
        v-if="notificationPicture"
        :src="`${backendURL}/${notificationPicture}`"
        class="max-w-18 h-auto"
      />
    </div>
    <div class="flex flex-col flex-grow">
      <p class="mb-1">
        {{
          $t(`notifications.${notification.text}.title`, {
            award: notification.award ? notification.award.title : '',
            badge: notification.badge ? notification.badge.title : '',
          })
        }}
      </p>
      <p class="text-$highlight text-sm">
        {{
          $t(`notifications.${notification.text}.text`, {
            campaign: notification.campaign ? notification.campaign.title : '',
            points: notification.scorePoints
              ? notification.scorePoints.toLocaleString()
              : '',
            author: notification.post
              ? !notification.post.author.deleted
                ? notification.post.author.username
                : $t('anonymous')
              : '',
            badge: notification.badge ? notification.badge.title : '',
            award: notification.award ? notification.award.title : '',
          })
        }}
      </p>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    notification: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const { $config } = useContext()

    const styleAttr = computed(() => {
      return props.notification.campaign
        ? `--highlight: ${props.notification.campaign.color};`
        : ''
    })

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

    return {
      styleAttr,
      notificationPicture,
      backendURL: $config.backendURL,
    }
  },
})
</script>
