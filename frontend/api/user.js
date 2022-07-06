import { useContext } from '@nuxtjs/composition-api'

export const userApi = () => {
  const { $api, $auth } = useContext()

  const finishTutorial = async () => {
    const res = await $api.patch(`users/${$auth.user.id}`, {
      tutorial: true
    })
    return res
  }

  const supplyUsername = async (username) => {
    const res = await $api.patch(`users/${$auth.user.id}`, {
      username
    })
    return res
  }

  const fetchUserInfoByCampaign = async (campaignId) => {
    const res = await $api.get(`campaign_members?user=${$auth.user.id}&campaign=${campaignId}`)
    return res[0]
  }

  return {
    finishTutorial,
    supplyUsername,
    fetchUserInfoByCampaign
  }
}