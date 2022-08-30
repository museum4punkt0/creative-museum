import { useContext } from '@nuxtjs/composition-api'

export const awardApi = () => {
  const { $api } = useContext()

  const fetchAwards = async (campaign) => {

    let res = null

    if (campaign) {
      res = await $api.get(`awards?campaign=${campaign}`)
    } else {
      res = await $api.get('awards?campaign.active=1&order[campaign.start]=asc&order[price]=asc')
    }

    return res
  }

  return {
    fetchAwards,
  }
}
