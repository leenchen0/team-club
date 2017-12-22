import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as getters from './getters';
import * as types from './mutation-types';

Vue.use(Vuex);

export default new Vuex.Store({
  actions,
  getters,
  state: {
    user: null,
  },
  mutations: {
    [types.LOGIN](state, data) {
      state.user = data;
    },
    [types.LOGOUT](state) {
      state.user = null;
    },
    [types.UPDATE_AVATAR](state, newPath) {
      state.user.avatar = newPath;
    },
    [types.UPDATE_INFO](state, data) {
      state.user.name = data.name;
      state.user.email = data.email;
    },
  },
});
