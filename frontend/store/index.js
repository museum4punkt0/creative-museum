export const strict = false

export const state = () => ({
  showAddButton: false,
  currentCampaign: 0,
  newPostOnCampaign: 0,
  showLogin: false,
  showAddModal: false
})

export const mutations = {
  SHOW_ADD_BUTTON(state) {
    state.showAddButton = true
  },
  HIDE_ADD_BUTTON(state) {
    state.showAddButton = false
  },
  SHOW_ADD_MODAL(state) {
    state.showAddModal = true
  },
  HIDE_ADD_MODAL(state) {
    state.showAddModal = false
  },
  SET_CURRENT_CAMPAIGN(state, id) {
    state.currentCampaign = id
  },
  SET_NEW_POST_ON_CAMPAIGN(state, id) {
    state.newPostOnCampaign = id
  },
  RESET_NEW_POST_ON_CAMPAIGN(state) {
    state.newPostOnCampaign = 0
  },
  SHOW_LOGIN(state) {
    state.showLogin = true
  },
  HIDE_LOGIN(state) {
    state.showLogin = false
  },
}

export const actions = {
  showAddButton({ commit }) {
    commit('SHOW_ADD_BUTTON')
  },
  hideAddButton({ commit }) {
    commit('HIDE_ADD_BUTTON')
  },
  showAddModal({ commit }) {
    commit('SHOW_ADD_MODAL')
  },
  hideAddModal({ commit }) {
    commit('HIDE_ADD_MODAL')
  },
  setCurrentCampaign({ commit }, id) {
    commit('SET_CURRENT_CAMPAIGN', id)
  },
  setNewPostOnCampaign({ commit }, id) {
    commit('SET_NEW_POST_ON_CAMPAIGN', id)
  },
  resetNewPostOnCampaign({ commit }) {
    commit('RESET_NEW_POST_ON_CAMPAIGN')
  },
  showLogin({ commit }) {
    commit('SHOW_LOGIN')
  },
  hideLogin({ commit }) {
    commit('HIDE_LOGIN')
  },
}

export const getters = {
  showAddButton(state) {
    return state.showAddButton
  },
  showAddModal(state) {
    return state.showAddModal
  },
  currentCampaign(state) {
    return state.currentCampaign
  },
  newPostOnCampaign(state) {
    return state.newPostOnCampaign
  },
  showLogin(state) {
    return state.showLogin
  },
}
