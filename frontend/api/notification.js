import { useContext } from '@nuxtjs/composition-api'

export const notificationApi = () => {
  const { $api, $auth } = useContext()

  const getNotifications = async () => {
    const res = await $api.get(`notifications`)
    return res
  }

  return {
    getNotifications,
  }
}
