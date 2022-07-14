export const state = () => ({
  showAddButton: false,
  currentCampaign: 0,
  newPostOnCampaign: 0
})
export const mutations = {
  SHOW_ADD_BUTTON(state) {
    state.showAddButton = true
  },
  HIDE_ADD_BUTTON(state) {
    state.showAddButton = false
  },
  SET_CURRENT_CAMPAIGN(state, id) {
    state.currentCampaign = id
  },
  SET_NEW_POST_ON_CAMPAIGN(state, id) {
    state.newPostOnCampaign = id
  },
  RESET_NEW_POST_ON_CAMPAIGN(state) {
    state.newPostOnCampaign = 0
  }
}

export const actions = {
  showAddButton ({ commit }) {
    commit('SHOW_ADD_BUTTON')
  },
  hideAddButton ({ commit }) {
    commit('HIDE_ADD_BUTTON')
  },
  setCurrentCampaign({ commit }, id) {
    commit('SET_CURRENT_CAMPAIGN', id)
  },
  setNewPostOnCampaign({ commit }, id) {
    commit('SET_NEW_POST_ON_CAMPAIGN', id)
  },
  resetNewPostOnCampaign({ commit }) {
    commit('RESET_NEW_POST_ON_CAMPAIGN')
  }
}

export const getters = {
  showAddButton(state) {
    return state.showAddButton
  },
  currentCampaign(state) {
    return state.currentCampaign
  },
  newPostOnCampaign(state) {
    return state.newPostOnCampaign
  }
}