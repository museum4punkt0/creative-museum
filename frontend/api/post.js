import { useContext } from '@nuxtjs/composition-api'

export const postApi = () => {
  const { $api, $auth } = useContext()

  const fetchPost = async (postId) => {
    return await $api.get(`posts/${postId}`)
  }

  const getUserPosts = async () => {
    return await $api.get(`posts?author=${$auth.user.uuid}`)
  }

  const getUserBookmarks = async () => {
    return await $api.get(`users/${$auth.user.uuid}/bookmarks`)
  }

  const createTextPost = async (campaignId, title, body) => {
    const response = await $api.post('posts', {
      type: 'text',
      author: `/v1/users/${$auth.user.uuid}`,
      title,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
    })
    await $auth.fetchUser()

    return response
  }

  const createPicturePost = async (
    campaignId,
    title,
    body,
    picture,
    altText
  ) => {
    const form = new FormData()
    form.append('file', picture.file)
    form.append('description', altText)

    const response = await $api.post('media_objects', form)
    const fileId = response.id

    const postResponse =  await $api.post('posts', {
      type: 'image',
      author: `/v1/users/${$auth.user.uuid}`,
      title,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      files: [`/v1/media_objects/` + fileId],
    })
    await $auth.fetchUser()

    return postResponse
  }

  const createPlaylistPost = async (campaignId, playlistId) => {
    const response = await $api.post('posts', {
      type: 'playlist',
      author: `/v1/users/${$auth.user.uuid}`,
      campaign: `/v1/campaigns/${campaignId}`,
      linkedPlaylist: `/v1/playlists/${playlistId}`,
    })
    await $auth.fetchUser()

    return response
  }

  const createPollPost = async (campaignId, contents) => {
    const pollOptions = []

    for (const option of contents.options) {
      pollOptions.push({ title: option.value })
    }

    const response =  await $api.post('posts', {
      campaign: `/v1/campaigns/${campaignId}`,
      type: 'poll',
      author: `/v1/users/${$auth.user.uuid}`,
      question: contents.question,
      body: contents.description,
      pollOptions,
    })
    await $auth.fetchUser()

    return response
  }

  const votePollOption = async (pollOptionId) => {
    return await $api.post('poll_option_choices', {
      pollOption: `/v1/poll_options/${pollOptionId}`,
      user: `/v1/users/${$auth.user.uuid}`,
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
    return await $api.post(`votes`, {
      post: `/v1/posts/${postId}`,
      direction,
      voter: `/v1/users/${$auth.user.uuid}`,
    })
  }

  const addToPlaylist = async (playlistId, postId) => {
    return await $api.get(`playlists/${playlistId}/add-post/${postId}`)
  }

  const createPlaylistWithPost = async (postId, title) => {
    return await $api.post(`playlists`, {
      creator: `/v1/users/${$auth.user.uuid}`,
      title,
      posts: [`/v1/posts/${postId}`],
    })
  }

  const createAudioPost = async (campaignId, title, description, audio) => {
    const response = await $api.post(`audio`, {
      creator: `/v1/users/${$auth.user.uuid}`,
      campaign: `/v1/campaigns/${campaignId}`,
      title,
      description,
      audio,
    })

    await $auth.fetchUser()

    return response
  }

  const submitCommentByPost = async (postId, body, campaignId) => {
    return await $api.post(`posts/${postId}/comments`, {
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      postType: 'text',
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
    submitCommentByPost,
    createPlaylistWithPost,
    createPlaylistPost,
    createPollPost,
    createAudioPost,
    votePollOption,
    getUserPosts,
    getUserBookmarks,
  }
}
