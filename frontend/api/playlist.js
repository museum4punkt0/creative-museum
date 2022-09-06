import { useContext } from '@nuxtjs/composition-api'

export const playlistApi = () => {
  const { $api } = useContext()

  const fetchPlaylist = async (playlistId) => {
    const res = await $api.get(`playlists/${playlistId}`)
    return res
  }

  return {
    fetchPlaylist,
  }
}
