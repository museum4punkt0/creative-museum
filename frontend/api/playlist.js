import { useContext } from '@nuxtjs/composition-api'

export const playlistApi = () => {
  const { $api } = useContext()

  async function fetchPlaylist(playlistId, page) {
    const response = await $api.get(`playlists/${playlistId}?&page=${page}`)
    return response
  }

  return {
    fetchPlaylist,
  }
}
