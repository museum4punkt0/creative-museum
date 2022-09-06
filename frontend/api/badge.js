import { useContext } from '@nuxtjs/composition-api'

export const badgeApi = () => {
  const { $api, $auth } = useContext()

  const fetchBadges = async (campaign) => {

    let response = null

    if (campaign) {
      response = await $api.get(`badges?campaign=${campaign}`)
    } else {
      response = await $api.get('badges?campaign.active=1&order[campaign.start]=asc&order[threshold]=asc')
    }

    return response
  }

  const fetchBadged = async () => {
    return await $api.get(`badgeds?user=${$auth.user.id}`)
  }

  return {
    fetchBadges,
    fetchBadged
  }
}
