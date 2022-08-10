import { useContext } from '@nuxtjs/composition-api'

export const feedbackApi = () => {
  const { $api, $auth } = useContext()

  const getOptions = async (campaignId) => {
    return await $api.get(`campaign_feedback_options?campaign=${campaignId}`)
  }

  const selectOption = async (postId, optionId) => {
    return await $api.post(`post_feedbacks`, {
      user: `v1/users/${$auth.user.uuid}`,
      post: `v1/posts/${postId}`,
      selection: `v1/campaign_feedback_options/${optionId}`
    })
  }

  const getFeedbackResults = async (postId) => {
    return await $api.get(`posts/${postId}/feedback_results`)
  }

  return {
    getOptions,
    selectOption,
    getFeedbackResults
  }
}
