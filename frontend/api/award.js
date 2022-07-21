import { useContext } from '@nuxtjs/composition-api'

export const awardApi = () => {
  const { $api } = useContext()

  const fetchAwardedByCampaignAndUser = async () => {
    const res = await $api.get('awards')
    return res
  }

  return {
    fetchAwardedByCampaignAndUser,
  }
}