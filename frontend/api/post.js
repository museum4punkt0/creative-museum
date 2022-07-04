import { useContext } from '@nuxtjs/composition-api'

export const postApi = () => {
  const { $api } = useContext()

  const fetchPost = async (postId) => {
    const res = await $api.get(`posts/${postId}`)
    return res
  }

  const fetchPostsByCampaign = async (campaignId) => {
    const res = await $api.get(`posts/?campaign=${campaignId}`)
    return res
  }

  const fetchPostsByPost = async (postId) => {
    const res = await $api.get(`posts/${postId}/comments`)
    return res
  }

  return {
    fetchPost,
    fetchPostsByCampaign,
    fetchPostsByPost
  }
}