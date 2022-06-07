import { useContext } from '@nuxtjs/composition-api'

export const userApi = () => {
  const { $api } = useContext()

  const finishTutorial = async (userId) => {
    const res = await $api.patch(`users/${userId}`, {
      tutorial: true
    })
    return res
  }
  return {
    finishTutorial,
  }
}