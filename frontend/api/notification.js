import { useContext } from '@nuxtjs/composition-api'

export const notificationApi = () => {
  const { $auth, $api } = useContext()

  async function fetchNotifications(campaign) {
    if (campaign) {
      return await $api.get(
        `notifications?campaign=${campaign}&receiver=${$auth.user.id}`
      )
    }

    return await $api.get(`notifications?receiver=${$auth.user.id}`)
  }

  async function fetchUnviewedNotifications() {
    return await $api.get(`notifications?viewed=0&receiver=${$auth.user.id}`)
  }

  async function updateNotificationAsViewed(notificationId) {
    return await $api.patch(`notifications/${notificationId}`, {
      receiver: `/v1/users/${$auth.user.uuid}`,
      viewed: true,
    })
  }

  return {
    fetchNotifications,
    fetchUnviewedNotifications,
    updateNotificationAsViewed,
  }
}
