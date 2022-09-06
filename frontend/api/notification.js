import { useContext } from '@nuxtjs/composition-api'

export const notificationApi = () => {
  const { $api } = useContext()

  const getNotifications = async (campaign) => {
    let response = null

    if (campaign) {
      response = await $api.get(`notifications?campaign=${campaign}`)
    } else {
      response = await $api.get('notifications')
    }

    return response
  }

  return {
    getNotifications,
  }
}
