import { useContext, useStore } from '@nuxtjs/composition-api'

export const feedbackApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  async function getOptions(campaignId) {
    return await $api.get(`campaign_feedback_options?campaign=${campaignId}`)
  }

  async function selectOption(postId, optionId) {
    const response = await $api.post(`post_feedbacks`, {
      user: `v1/users/${$auth.user.uuid}`,
      post: `v1/posts/${postId}`,
      selection: `v1/campaign_feedback_options/${optionId}`,
    })

    $auth.fetchUser()
    store.dispatch('updateUser')

    return response
  }

  async function getFeedbackResults(postId) {
    return await $api.get(`posts/${postId}/feedback_results`)
  }

  return {
    getOptions,
    selectOption,
    getFeedbackResults,
  }
}
