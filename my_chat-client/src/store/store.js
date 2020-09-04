import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex)

 const store = new Vuex.Store({
    state: {
        user:{
            id: 0,
            username: "",
            token: "",
        },
        authenticated: false,
        currentchannel: 0,
        messages: [],
    },
    mutations: {
        getAuthenticated(state) {
            return state.authenticated;
        },
        setAuthenticated(state, isauth) {
            state.authenticated = isauth;
        },

        setChannel(state, channel_id) {
            state.currentchannel = channel_id;
        },

        setUser (state, payload) {
            state.user.id = payload.id;
            state.user.username = payload.username;
            state.user.token = payload.token;
        },

        getUsername (state) {
            return state.user.username;
        },
        getToken (state) {
            return state.user.token;
        },
        getId (state) {
            return state.user.id;
        },

        setMessages(state, messages) {
            state.messages = messages;
        },
        getMessages (state) {
            return state.messages;
        },

    },
     plugins: [createPersistedState()]
});
export default store;