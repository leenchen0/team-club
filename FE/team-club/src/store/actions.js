import * as types from './mutation-types';
import * as service from '../service';

export const getUserInfo = ({ commit }, next) => {
  service.getUserInfo().then((data) => {
    if (!data.error) {
      commit(types.LOGIN, data.data);
    }
    next();
  }).catch(() => { });
};
