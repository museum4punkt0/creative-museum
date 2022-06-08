import { useContext } from '@nuxtjs/composition-api'

export const userApi = () => {
  const { $api } = useContext()

  const finishTutorial = async (userId) => {
    const res = await $api.patch(`users/${userId}`, {
      tutorial: true
    })
    return res
  }

  const supplyUsername = async (userId, username) => {
    const res = await $api.patch(`users/${userId}`, {
      username
    })
    return res
  }

  return {
    finishTutorial,
    supplyUsername
  }
}