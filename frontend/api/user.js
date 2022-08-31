import { useContext } from '@nuxtjs/composition-api'

export const userApi = () => {
  const { $api, $auth } = useContext()

  const finishTutorial = async () => {
    const res = await $api.patch(`users/${$auth.user.uuid}`, {
      tutorial: true,
    })
    return res
  }

  const supplyUsername = async (username) => {
    const res = await $api.patch(`users/${$auth.user.uuid}`, {
      username,
    })
    return res
  }

  const fetchUserInfoByCampaign = async (campaignId) => {
    const res = await $api.get(
      `campaign_members?user=${$auth.user.id}&campaign=${campaignId}`
    )
    return res[0]
  }

  const searchUser = async (searchString) => {
    return await $api.get(
      `users?or[fullName]=${searchString}&or[email]=${searchString}`
    )
  }

  const updateUser = async (userData) => {
    delete userData.uuid
    delete userData.id

    let pictureData = null

    if ('picture' in userData) {
      pictureData = userData.picture
      delete userData.picture
    }

    const res = await $api
      .patch(`users/${$auth.user.uuid}`, userData)
      .then(async () => {
        if (pictureData !== null) {
          const form = new FormData()
          form.append('file', pictureData.file)
          form.append(
            'description',
            userData.firstName + ' ' + userData.lastName
          )

          const response = await $api.post('media_objects', form)
          const fileId = response.id

          await $api.patch(`users/${$auth.user.uuid}`, {
            profilePicture: `/v1/media_objects/` + fileId,
          })
        }
        $auth.fetchUser()
      })
    return res
  }

  return {
    finishTutorial,
    supplyUsername,
    fetchUserInfoByCampaign,
    updateUser,
    searchUser,
  }
}
