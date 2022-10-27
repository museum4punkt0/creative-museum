import { useContext, useStore } from '@nuxtjs/composition-api'

export const awardApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  async function fetchAwards(campaign) {
    let response = null

    if (campaign) {
      response = await $api.get(`awards?campaign=${campaign}`)
    } else {
      response = await $api.get(
        'awards?campaign.active=1&order[campaign.start]=asc&order[price]=asc'
      )
    }

    return response
  }

  async function fetchAvailableAwards(campaign) {
    const response = await $api.get(`campaigns/${campaign}/awards/available`)
    return response
  }

  async function fetchAvailableSoonAwards(campaign) {
    const response = await $api.get(`campaigns/${campaign}/awards/availablesoon`)
    return response
  }

  async function fetchAwarded(campaign, user) {
    let campaignFilterString = ''
    if(campaign){
      campaignFilterString = `&award.campaign=${campaign}`
    }

    return await $api.get(`awardeds?winner=${user ? user.id : $auth.user.id}${campaignFilterString}`)
  }

  async function fetchGiftedAwards(campaign, user) {
    let campaignFilterString = ''
    if(campaign){
      campaignFilterString = `&award.campaign=${campaign}`
    }

    return await $api.get(`awardeds?giver=${user ? user.id : $auth.user.id}${campaignFilterString}`)
  }

  async function awardUser(awardId, userId) {
    const response = await $api.post(`awardeds`, {
      giver: `/v1/users/${$auth.user.uuid}`,
      winner: `/v1/users/${userId}`,
      award: `/v1/awards/${awardId}`,
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  return {
    fetchAwards,
    fetchAvailableAwards,
    fetchAvailableSoonAwards,
    fetchAwarded,
    awardUser,
    fetchGiftedAwards
  }
}
