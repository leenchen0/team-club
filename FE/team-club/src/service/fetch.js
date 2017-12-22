import { Loading } from 'element-ui';
import router from '../router';

// const BASE_URL = 'http://localhost:8001';

let showLoading = false;
let loadingInstance = null;

const fetch = (url, option) => new Promise((resolve, reject) => {
  showLoading = true;
  setTimeout(() => {
    if (showLoading) {
      loadingInstance = Loading.service({ fullscreen: true });
    }
  }, 1000);

  option = option || {};
  if (option.headers === undefined) {
    option.headers = { 'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8' };
  }
  option.credentials = 'include';
  // window.fetch(BASE_URL + url, option).then((response) => {
  window.fetch(url, option).then((response) => {
    showLoading = false;
    loadingInstance && loadingInstance.close();
    if (!response.ok) {
      throw new Error();
    }
    return response.json();
  }).then((data) => {
    if (data.code === 10011) {
      // 未登录 跳转到登录页面
      router.push({ name: 'Login' });
    } else {
      resolve(data);
    }
  }).catch((err) => {
    reject(err);
  });
});

export default fetch;
