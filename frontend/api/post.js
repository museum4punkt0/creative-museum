import { useContext } from '@nuxtjs/composition-api'

export const postApi = () => {
  const { $api, $auth } = useContext()

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

  const votePost = async (postId, direction) => {
    const res = await $api.post(`votes`,{
      post: `/v1/posts/${postId}`,
      direction,
      voter: `/v1/users/${$auth.user.uuid}`
    })
    return res
  }

  const fetchYourVoteByPost = async (postId) => {
    const res = await $api.get(`votes?voter=${$auth.user.id}&post=${postId}`)
    return res[0]
  }

  return {
    fetchPost,
    fetchPostsByCampaign,
    fetchPostsByPost,
    votePost,
    fetchYourVoteByPost
  }
}