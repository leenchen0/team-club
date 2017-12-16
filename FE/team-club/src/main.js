// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import ElementUI from 'element-ui';
import mavonEditor from 'mavon-editor';
import 'mavon-editor/dist/css/index.css';
import './theme.scss';
import App from './App';
import router from './router';

Vue.use(ElementUI);
Vue.use(mavonEditor);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App },
});
