import { useContext } from '@nuxtjs/composition-api'

export const playlistApi = () => {
  const { $api } = useContext()

  async function fetchPlaylist(playlistId, page) {
    const res = await $api.get(`playlists/${playlistId}?&page=${page}`)
    return res
  }

  return {
    fetchPlaylist,
  }
}
