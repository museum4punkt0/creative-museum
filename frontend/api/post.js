import { useContext, useStore } from '@nuxtjs/composition-api'

export const postApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  async function fetchPost(postId) {
    return await $api.get(`posts/${postId}`)
  }

  async function fetchUserPosts(userId, page) {
    return await $api.get(`posts?author=${userId}&page=${page}`)
  }

  async function fetchUserBookmarks() {
    return await $api.get(`users/${$auth.user.uuid}/bookmarks`)
  }

  async function createTextPost(campaignId, title, body) {
    const response = await $api.post('posts', {
      type: 'text',
      author: `/v1/users/${$auth.user.uuid}`,
      title,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
    })

    $auth.fetchUser()
    store.dispatch('updateUser')
    store.dispatch('awardsChange')

    return response
  }

  async function createImagePost(campaignId, title, body, image, altText) {
    const form = new FormData()
    form.append('file', image.file)
    form.append('description', altText)
    form.append('type', 'image')

    const response = await $api.post('media_objects', form)
    const fileId = response.id

    const postResponse = await $api.post('posts', {
      type: 'image',
      author: `/v1/users/${$auth.user.uuid}`,
      title,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      files: [`/v1/media_objects/` + fileId],
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return postResponse
  }

  async function createVideoPost(campaignId, title, body, video, altText) {
    const form = new FormData()
    form.append('file', video.file)
    form.append('description', altText)
    form.append('type', 'video')

    const response = await $api.post('media_objects', form)
    const fileId = response.id

    const postResponse = await $api.post('posts', {
      type: 'video',
      author: `/v1/users/${$auth.user.uuid}`,
      title,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      files: [`/v1/media_objects/` + fileId],
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return postResponse
  }

  async function createPlaylistPost(campaignId, playlistId) {
    const response = await $api.post('posts', {
      type: 'playlist',
      author: `/v1/users/${$auth.user.uuid}`,
      campaign: `/v1/campaigns/${campaignId}`,
      linkedPlaylist: `/v1/playlists/${playlistId}`,
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  async function createPollPost(campaignId, contents) {
    const pollOptions = []

    for (const option of contents.options) {
      pollOptions.push({ title: option.value })
    }

    const response = await $api.post('posts', {
      campaign: `/v1/campaigns/${campaignId}`,
      type: 'poll',
      author: `/v1/users/${$auth.user.uuid}`,
      question: contents.question,
      body: contents.description,
      pollOptions,
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  async function votePollOption(pollOptionId) {
    return await $api.post('poll_option_choices', {
      pollOption: `/v1/poll_options/${pollOptionId}`,
      user: `/v1/users/${$auth.user.uuid}`,
    })
  }

  async function fetchPostsByCampaign(campaignId, sorting, direction, page) {
    let orderParams = ''
    const directionKey = direction === 'asc' ? 'asc' : 'desc'

    if (sorting === 'feedback') {
      orderParams = `&order[leadingFeedbackCount]=${directionKey}&leadingFeedbackOption=${store.state.filterId}`
    }
    if (sorting === 'date') {
      orderParams = `&order[created]=${directionKey}`
    }
    if (sorting === 'votestotal') {
      orderParams = `&order[votestotal]=${directionKey}`
    }
    if (sorting === 'playlist') {
      orderParams = `&order[created]=${directionKey}&type=playlist`
    }
    if (sorting === 'controversial') {
      orderParams = `&order[commentCount]=${directionKey}`
    }

    return await $api.get(
      `posts?campaign=${campaignId}${orderParams}&page=${page}`
    )
  }

  async function fetchPostsByPost(postId) {
    return await $api.get(`posts/${postId}/comments?order[created]=asc`)
  }

  async function toggleBookmark(postId) {
    return await $api.get(`posts/${postId}/bookmark`)
  }

  async function votePost(postId, direction) {
    const response = await $api.post(`votes`, {
      post: `/v1/posts/${postId}`,
      direction,
      voter: `/v1/users/${$auth.user.uuid}`,
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  async function addToPlaylist(playlistId, postId) {
    return await $api.get(`playlists/${playlistId}/add-post/${postId}`)
  }

  async function createPlaylistWithPost(postId, title) {
    return await $api.post(`playlists`, {
      creator: `/v1/users/${$auth.user.uuid}`,
      title,
      posts: [`/v1/posts/${postId}`],
    })
  }

  async function createAudioPost(campaignId, title, audio, image) {
    const postBody = {
      author: `/v1/users/${$auth.user.uuid}`,
      campaign: `/v1/campaigns/${campaignId}`,
      type: 'audio',
      title,
      files: [],
    }

    if (audio) {
      const formAudio = new FormData()
      formAudio.append('file', audio, 'voicemessage.wav')
      formAudio.append('type', 'audio')
      const responseAudio = await $api.post('media_objects', formAudio)
      const audioFileId = responseAudio.id
      postBody.files.push(`/v1/media_objects/` + audioFileId)
    }

    if (image) {
      const formImage = new FormData()
      formImage.append('file', image.file)
      formImage.append('type', 'image')
      const responseImage = await $api.post('media_objects', formImage)
      const imageFileId = responseImage.id
      postBody.files.push(`/v1/media_objects/` + imageFileId)
    }

    const response = await $api.post('posts', postBody)

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  async function submitCommentByPost(postId, body, campaignId) {
    const response = await $api.post(`posts/${postId}/comments`, {
      author: `/v1/users/${$auth.user.uuid}`,
      body,
      campaign: `/v1/campaigns/${campaignId}`,
      postType: 'text',
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  async function deletePostById(postId) {
    return await $api.delete(`posts/${postId}`)
  }

  async function reportPostById(postId) {
    const response = await $api.patch(`posts/${postId}/report`, {})

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  return {
    fetchPost,
    toggleBookmark,
    createTextPost,
    createImagePost,
    createVideoPost,
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
    fetchUserPosts,
    fetchUserBookmarks,
    deletePostById,
    reportPostById
  }
}
