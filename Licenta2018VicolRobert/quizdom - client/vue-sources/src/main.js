import Vue from 'vue'
import App from './App'
import router from './router'
import { store } from './store'
import VueResource from 'vue-resource'
import VueCordova from 'vue-cordova'
import Carousel3d from 'vue-carousel-3d';
import SweetModal from 'sweet-modal-vue/src/plugin.js'
import Echo from 'laravel-echo'
import axios from 'axios';

window.Pusher =  require('pusher-js')

Vue.config.productionTip = false

Vue.use(VueCordova)
Vue.use(VueResource)
Vue.use(Carousel3d)
Vue.use(SweetModal)

Vue.http.interceptors.push(function (request, next) {
  let token = window.localStorage.getItem('TOKEN')
  if (token) {
    request.headers.set('Authorization', 'Bearer ' + token)
  }
  next()
})
var token = window.localStorage.getItem('TOKEN')
Vue.prototype.$axios = axios;

axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

Vue.prototype.$publicurl = 'http://127.0.0.1:8000/'
Vue.prototype.$apiurl =  Vue.prototype.$publicurl+'api/'

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '531134214748a03bdcb5',
  cluster:'eu',
  encrypted:true
})
// Vue.prototype.conn = new WebSocket('ws://localhost:8087');
// Vue.prototype.conn.onopen = function (e) {
//   console.log("Connection established!");
//   var obj = {
//     john : "travolta"
//   }
//   // Vue.prototype.conn.send(JSON.stringify(obj));
// };
//   Vue.prototype.conn.onmessage = function(e) {
//   var msg = JSON.parse(e.data);
//   console.log(msg)
// };


/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: {App,Vue,Carousel3d}
})
