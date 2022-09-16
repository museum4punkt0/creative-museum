import { useContext, useStore } from '@nuxtjs/composition-api'

export const awardApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  const fetchAwards = async (campaign) => {

    let response = null

    if (campaign) {
      response = await $api.get(`awards?campaign=${campaign}`)
    } else {
      response = await $api.get('awards?campaign.active=1&order[campaign.start]=asc&order[price]=asc')
    }

    return response
  }

  const fetchAwarded = async () => {
    return await $api.get(`awardeds?winner=${$auth.user.id}`)
  }

  const awardUser = async (awardId, userId) => {
    const response = await $api.post(`awardeds`, {
      giver: `/v1/users/${$auth.user.uuid}`,
      winner: `/v1/users/${userId}`,
      award: `/v1/awards/${awardId}`
    })

    $auth.fetchUser()
    store.dispatch('updateNotifications')

    return response
  }

  return {
    fetchAwards,
    fetchAwarded,
    awardUser,
  }
}
