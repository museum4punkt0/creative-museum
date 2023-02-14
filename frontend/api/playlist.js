import { useContext, useStore } from '@nuxtjs/composition-api'

export const playlistApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  async function fetchPlaylist(playlistId, page) {
    const response = await $api.get(`playlists/${playlistId}?&page=${page}`)
    return response
  }

  async function deletePlaylist(playlistId) {
    const response = await $api.delete(`playlists/${playlistId}`)

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  return {
    fetchPlaylist,
    deletePlaylist
  }
}
