export const state = () => ({
  showAddButton: false
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
  }
}

export const actions = {
  showAddButton ({ commit }) {
    commit('SHOW_ADD_BUTTON')
  },
  hideAddButton ({ commit }) {
    commit('HIDE_ADD_BUTTON')
  },
  setCurrentCampaign({ commit}, id) {
    commit('SET_CURRENT_CAMPAIGN', id)
  }
}

export const getters = {
  showAddButton(state) {
    return state.showAddButton
  },
  currentCampaign(state) {
    return state.currentCampaign
  }
}