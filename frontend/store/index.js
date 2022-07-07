export const state = () => ({
  showAddButton: false
})
export const mutations = {
  SHOW_ADD_BUTTON(state) {
    state.showAddButton = true
  },
  HIDE_ADD_BUTTON(state) {
    state.showAddButton = false
  }
}

export const actions = {
  showAddButton ({ commit }) {
    commit('SHOW_ADD_BUTTON')
  },
  hideAddButton ({ commit }) {
    commit('HIDE_ADD_BUTTON')
  }
}

export const getters = {
  showAddButton(state) {
    return state.showAddButton
  }
}