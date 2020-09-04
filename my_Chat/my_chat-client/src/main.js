import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.config.productionTip = false
Vue.use(VueRouter)

import MessagesList from './components/messages/Chatbox.vue';
import Dashboard from './components/Dashboard.vue';
import Login from './components/users/Login.vue';
import Register from './components/users/Register.vue';
import Userlist from './components/users/Userlist.vue';
import Profil from './components/users/Profil.vue';
import store from './store/store.js';
import axios from "axios";

const router = new VueRouter({
  routes : [
    { path: '/messages', component: MessagesList, name: 'messages'},
    { path: '/login', component: Login, name: 'login'},
    { path: '/register', component: Register, name: 'register'},
    { path: '/users', component: Userlist, name: 'users'},
    { path: '/profil/:id', component: Profil, name: 'profil', props: true},
  ]
})

axios.interceptors.request.use((config) => {
  let token = store.state.user.token;

  if (token) {
    config.headers['Authorization'] = `Bearer ${ token }`
  }

  return config
})

new Vue({
  router,
  render: h => h(Dashboard),
  store,
}).$mount('#app')