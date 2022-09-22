import { useContext } from '@nuxtjs/composition-api'

export const notificationApi = () => {
  const { $auth, $api } = useContext()

  const fetchNotifications = async (campaign) => {
    if (campaign) {
      return await $api.get(
        `notifications?campaign=${campaign}&receiver=${$auth.user.id}`
      )
    }

    return await $api.get(`notifications?receiver=${$auth.user.id}`)
  }

  const fetchUnviewedNotifications = async () => {
    return await $api.get(`notifications?viewed=0&receiver=${$auth.user.id}`)
  }

  const updateNotificationAsViewed = async (notificationId) => {
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
