import { useContext } from '@nuxtjs/composition-api'

export const awardApi = () => {
  const { $api, $auth } = useContext()

  const fetchAwards = async (campaign) => {

    let res = null

    if (campaign) {
      res = await $api.get(`awards?campaign=${campaign}`)
    } else {
      res = await $api.get('awards?campaign.active=1&order[campaign.start]=asc&order[price]=asc')
    }

    return res
  }

  const awardUser = async (awardId, userId) => {
    const response = await $api.post(`awardeds`, {
      giver: `/v1/users/${$auth.user.uuid}`,
      winner: `/v1/users/${userId}`,
      award: `/v1/awards/${awardId}`
    })

    await $auth.fetchUser()
    return response
  }

  return {
    fetchAwards,
    awardUser,
  }
}
