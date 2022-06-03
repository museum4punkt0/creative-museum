import { useContext } from '@nuxtjs/composition-api'

export const campaignApi = () => {
  const { $api } = useContext()

  const fetchCampaign = async (campaignId) => {
    const res = await $api.get(`campaigns/${campaignId}`)
    return res
  }

  const fetchCampaigns = async () => {
    const res = await $api.get('campaigns')
    return res
  }

  return {
    fetchCampaign,
    fetchCampaigns,
  }
}