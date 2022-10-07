export const strict = false

export const state = () => ({
  showAddButton: false,
  currentCampaign: 0,
  currentSorting: 'date',
  filterId: 0,
  currentSortingDirection: 'desc',
  newPostOnCampaign: 0,
  showLogin: false,
  showAddModal: false,
  showProfileUpdate: false,
  showTutorial: false,
  notificationsUpdated: false,
  notificationsCount: 0,
  splashscreenShown: false,
  awardsChange: false,
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
  SET_SORTING_FEEDBACK(state, id) {
    state.filterId = id
    state.currentSorting = 'feedback'
    state.currentSortingDirection = 'desc'
  },
  SET_CURRENT_SORTING(state, propertyName) {
    state.currentSorting = propertyName
  },
  SET_CURRENT_SORTING_DIRECTION(state, direction) {
    state.currentSortingDirection = direction
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
  SHOW_PROFILE_UPDATE(state) {
    state.showProfileUpdate = true
  },
  HIDE_PROFILE_UPDATE(state) {
    state.showProfileUpdate = false
  },
  SHOW_TUTORIAL(state) {
    state.showTutorial = true
  },
  HIDE_TUTORIAL(state) {
    state.showTutorial = false
  },
  UPDATE_NOTIFICATIONS(state) {
    state.notificationsUpdated = false
  },
  UPDATED_NOTIFICATIONS(state, length) {
    state.notificationsUpdated = true
    state.notificationCount = length
  },
  SPLASHSCREEN_SHOWN(state) {
    state.splashscreenShown = true
  },
  AWARDS_CHANGE(state) {
    state.awardsChange = true
  },
  AWARDS_CHANGED(state) {
    state.awardsChange = false
  }
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
  setCurrentSorting({ commit }, propertyName) {
    commit('SET_CURRENT_SORTING', propertyName)
  },
  setCurrentSortingDirection({ commit }, direction) {
    commit('SET_CURRENT_SORTING_DIRECTION', direction)
  },
  setSortByCampaignFeedback({ commit }, id) {
    commit('SET_SORTING_FEEDBACK', id)
  },
  setCurrentSortingWithDirection({ commit }, data) {
    commit('SET_CURRENT_SORTING', data[0])
    commit('SET_CURRENT_SORTING_DIRECTION', data[1])
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
  showProfileUpdate({ commit }) {
    commit('SHOW_PROFILE_UPDATE')
  },
  hideProfileUpdate({ commit }) {
    commit('HIDE_PROFILE_UPDATE')
  },
  showTutorial({ commit }) {
    commit('SHOW_TUTORIAL')
  },
  hideTutorial({ commit }) {
    commit('HIDE_TUTORIAL')
  },
  updateUser({ commit }) {
    commit('UPDATE_NOTIFICATIONS')
    commit('AWARDS_CHANGE')
  },
  updatedNotifications({ commit }, length) {
    commit('UPDATED_NOTIFICATIONS', length)
  },
  splashscreenShown({ commit }) {
    commit('SPLASHSCREEN_SHOWN')
  },
  awardsChange({ commit }) {
    commit('AWARDS_CHANGE')
  },
  awardsChanged({ commit }) {
    commit('AWARDS_CHANGED')
  }
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
  showProfileUpdate(state) {
    return state.showProfileUpdate
  },
  showTutorial(state) {
    return state.showTutorial
  },
  notificationsUpdated(state) {
    return state.notificationsUpdated
  },
  splashscreenShown(state) {
    return state.splashscreenShown
  },
  awardsChange(state) {
    return state.awardsChange
  },
  notificationsCount(state) {
    return state.notificationsCount
  }
}
