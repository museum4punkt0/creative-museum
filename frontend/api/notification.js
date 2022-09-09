import { useContext } from '@nuxtjs/composition-api'

export const notificationApi = () => {
  const { $auth, $api } = useContext()

  const fetchNotifications = async (campaign) => {
    if (campaign) {
      return await $api.get(`notifications?campaign=${campaign}&receiver=${$auth.user.id}`)
    }

    return await $api.get(`notifications?receiver=${$auth.user.id}`)
  }

  return {
    fetchNotifications,
  }
}
