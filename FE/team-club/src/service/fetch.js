import { Loading } from 'element-ui';
import router from '../router';

const fetch = (url, option) => new Promise((resolve, reject) => {
  const loadingInstance = Loading.service({ fullscreen: true });
  const token = window.localStorage.getItem('token');
  option = option || {};
  option.headers = { 'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8', token: token };
  window.fetch(url, option).then((response) => {
    loadingInstance.close();
    if (!response.ok) {
      throw new Error();
    }
    return response.json();
  }).then((data) => {
    if (data.code === 10011) {
      window.localStorage.removeItem('token');
      // 未登录 跳转到登录页面
      router.push('/');
    } else {
      resolve(data);
    }
  }).catch((err) => {
    reject(err);
  });
});

export default fetch;
