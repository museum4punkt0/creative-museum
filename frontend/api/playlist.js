import { useContext } from '@nuxtjs/composition-api'

export const playlistApi = () => {
  const { $api } = useContext()

  const fetchPlaylist = async (playlistId, page) => {
    const res = await $api.get(`playlists/${playlistId}?&page=${page}`)
    return res
  }

  return {
    fetchPlaylist,
  }
}
