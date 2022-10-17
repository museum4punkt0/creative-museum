import { useContext, useStore } from '@nuxtjs/composition-api'

export const badgeApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  const fetchBadges = async (campaign) => {
    let response = null

    if (campaign) {
      response = await $api.get(`badges?campaign=${campaign}`)
    } else {
      response = await $api.get(
        'badges?campaign.active=1&order[campaign.start]=asc&order[threshold]=asc'
      )
    }

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  const fetchBadged = async (user) => {
    return await $api.get(`badgeds?user=${user ? user.id : $auth.user.id}`)
  }

  return {
    fetchBadges,
    fetchBadged,
  }
}
