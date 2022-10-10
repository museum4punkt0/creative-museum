import { useContext } from '@nuxtjs/composition-api'

export const campaignApi = () => {
  const { $api } = useContext()

  const fetchCampaign = async (campaignId) => {
    const res = await $api.get(`campaigns/${campaignId}`)
    return res
  }

  const fetchCampaigns = async () => {
    return await $api.get('campaigns?published=1')
  }

  const fetchCampaignResult = async (campaignId) => {
    return await $api.get(`campaigns/result/${campaignId}`)
  }

  return {
    fetchCampaign,
    fetchCampaigns,
    fetchCampaignResult
  }
}
