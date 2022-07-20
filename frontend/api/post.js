import { useContext } from '@nuxtjs/composition-api'

export const postApi = () => {
  const { $api, $auth } = useContext()

  const fetchPost = async (postId) => {
    const res = await $api.get(`posts/${postId}`)
    return res
  }

  const createTextPost = async (campaignId, body) => {
    const res = await $api.post('posts', {
      type: 'text',
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
    })
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

  const toggleBookmark = async (postId) => {
    return await $api.get(`posts/${postId}/bookmark`)
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

  const submitCommentByPost = async (postId, body, campaignId) => {
    const res = await $api.post(`posts/${postId}/comments`, {
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      postType: 'text'
    })
    return res
  }

  return {
    fetchPost,
    toggleBookmark,
    createTextPost,
    fetchPostsByCampaign,
    fetchPostsByPost,
    votePost,
    fetchYourVoteByPost,
    submitCommentByPost
  }
}
