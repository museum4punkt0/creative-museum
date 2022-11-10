import { useContext } from '@nuxtjs/composition-api'

export const campaignApi = () => {
  const { $api } = useContext()

  async function fetchCampaign(campaignId) {
    const res = await $api.get(`campaigns/${campaignId}`)
    return res
  }

  async function fetchCampaigns() {
    return await $api.get('campaigns?published=1&active=1')
  }

  async function fetchCampaignResult(campaignId) {
    return await $api.get(`campaigns/result/${campaignId}`)
  }

  return {
    fetchCampaign,
    fetchCampaigns,
    fetchCampaignResult
  }
}
