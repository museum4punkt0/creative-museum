export const state = () => ({
  authenticated: false
})

export const getters = () => ({
  isAuth: state => state.authenticated
})

export const mutations = () => ({
  login (state) {
    console.log('authenticated!')
    Vue.set(state, 'authenticated', true)
  },
  logout (state) {
    Vue.set(state, 'authenticated', false)
  }
})

export const actions = () => ({
  nuxtServerInit({ commit }, { req }) {
    if (req.session && req.session.authUser) {
      commit('login')
    } else {
      commit('logout')
    }
  },
  login ({ commit }) {
    commit('login')
  },

  logout ({ commit }) {
    commit('logout')
  }
})