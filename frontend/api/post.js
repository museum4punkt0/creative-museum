import { useContext } from '@nuxtjs/composition-api'

export const postApi = () => {
  const { $api, $auth } = useContext()

  const fetchPost = async (postId) => {
    return await $api.get(`posts/${postId}`)
  }

  const createTextPost = async (campaignId, body) => {
    return await $api.post('posts', {
      type: 'text',
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
    })
  }

  const createPicturePost = async (campaignId, body, picture) => {

    let form = new FormData()
    form.append('file', picture[0].file)

    const response = await $api.post('media_objects', form)
    const fileId = response.id

    return await $api.post('posts', {
      type: 'image',
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      files: [
        `/v1/media_objects/` + fileId
      ]
    })
  }

  const createPlaylistPost = async (campaignId, playlistId) => {
    return await $api.post('posts', {
      type: 'playlist',
      author: `/v1/users/${$auth.user.uuid}`,
      campaign: `/v1/campaigns/${campaignId}`,
      linkedPlaylist: `/v1/playlists/${playlistId}`
    })
  }

  const fetchPostsByCampaign = async (campaignId) => {
    return await $api.get(`posts/?campaign=${campaignId}`)
  }

  const fetchPostsByPost = async (postId) => {
    return await $api.get(`posts/${postId}/comments?order[created]=asc`)
  }

  const toggleBookmark = async (postId) => {
    return await $api.get(`posts/${postId}/bookmark`)
  }

  const votePost = async (postId, direction) => {
    return await $api.post(`votes`,{
      post: `/v1/posts/${postId}`,
      direction,
      voter: `/v1/users/${$auth.user.uuid}`
    })
  }

  const addToPlaylist = async (playlistId, postId) => {
    return await $api.get(`playlists/${playlistId}/add-post/${postId}`)
  }

  const fetchYourVoteByPost = async (postId) => {
    const res = await $api.get(`votes?voter=${$auth.user.id}&post=${postId}`)
    return res[0]
  }

  const createPlaylistWithPost = async (postId, title) => {
    return await $api.post(`playlists`, {
      creator: `/v1/users/${$auth.user.uuid}`,
      title,
      posts: [
        `/v1/posts/${postId}`
      ]
    })
  }

  const submitCommentByPost = async (postId, body, campaignId) => {
    return await $api.post(`posts/${postId}/comments`, {
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      postType: 'text'
    })
  }

  return {
    fetchPost,
    toggleBookmark,
    createTextPost,
    createPicturePost,
    fetchPostsByCampaign,
    fetchPostsByPost,
    votePost,
    addToPlaylist,
    fetchYourVoteByPost,
    submitCommentByPost,
    createPlaylistWithPost,
    createPlaylistPost
  }
}
