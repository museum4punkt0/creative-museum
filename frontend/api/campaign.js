import { useContext } from '@nuxtjs/composition-api'

export const campaignApi = () => {
  const { $axios } = useContext()

  const fetchCampaign = async (campaignId) => {
    const res = await $axios.$get(`campaigns/${campaignId}`)

    return res.data
  }

  const fetchCampaigns = async () => {
    const res = await $axios.get('campaigns')

    return res.data
  }

  return {
    fetchCampaign,
    fetchCampaigns,
  }
}