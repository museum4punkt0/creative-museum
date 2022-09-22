import { useContext, useStore } from '@nuxtjs/composition-api'

export const userApi = () => {
  const { $api, $auth } = useContext()

  async function finishTutorial() {
    const res = await $api.patch(`users/${$auth.user.uuid}`, {
      tutorial: true,
    })
    return res
  }

  async function supplyUsername(username) {
    const res = await $api.patch(`users/${$auth.user.uuid}`, {
      username,
    })
    return res
  }

  async function fetchUser(uuid) {
    const res = await $api.get(
      `users/${uuid}`
    )
    return res
  }

  async function fetchUserInfoByCampaign(campaignId) {
    const res = await $api.get(
      `campaign_members?user=${$auth.user.id}&campaign=${campaignId}`
    )
    return res[0]
  }

  async function searchUser(searchString) {
    return await $api.get(
      `users?or[fullName]=${searchString}&or[email]=${searchString}&or[username]=${searchString}`
    )
  }

  async function updateUser(userData) {
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

  async function deleteUser(anonymizeOrDelete) {
    if (anonymizeOrDelete === 'delete') {
      const res = await $api.delete(`users/me`)
      return res
    } else {
      const res = await $api.patch(`users/me/anonymize`)
      return res
    }
  }

  return {
    finishTutorial,
    supplyUsername,
    fetchUser,
    fetchUserInfoByCampaign,
    updateUser,
    deleteUser,
    searchUser,
  }
}
