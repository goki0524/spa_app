import Vue from 'vue'
import router from './router'
import http from './services/http.js'
import 'bootstrap';

const app = new Vue({
  router,
  el: '#app',
  created () {
    http.init()
  },
  render: h => h(require('./app.vue')),
}).$mount('#app')