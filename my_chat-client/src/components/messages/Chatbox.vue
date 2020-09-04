<template>
    <section class="row justify">
            <Userlist class="userlist col-2 radius" @selectChannel='updateMessages()'></Userlist>
            <div class="chatbox col-9 ml-2 radius">
                <div class="messagelist">
                    <div v-for="message in messages" v-bind:key="message.id" class="d-flex" v-bind:class="{ 'justify-content-end' : currentuser === message.user }">
                        <div class="messagebox radius p-2">
                            <button class="radius deletemess ml-5" v-on:click="delete_this(message.id)"><img src="../../assets/croix.png"></button>
                            <div class="sender row"><b>{{ message.user }}</b></div>
                            <div class="message">{{ message.contenu }}</div>
                        </div>
                    </div>
                </div>
                <Messageform class="sendbox" @selectChannel='updateMessages()'></Messageform>
            </div>
    </section>
</template>

<script>
import axios from 'axios'
import Messageform from './Messageform.vue'
import Userlist from '../users/Userlist.vue'

export default {
    components: {
      Messageform, Userlist,
    },
    data() {
        return {
            messages: [],
            currentuser: this.$store.state.user.username,
        }
    },
    created() {
        let store = this.$store;
        console.log(store.state.messages);
    },
    methods: {
        delete_this(idkey) {
            let url = 'http://localhost:8000/api/messages/' + idkey.toString();
            axios.delete(url)
                .then(response => this.messages = response.data);
        },
        updateMessages() {
            let store = this.$store;
            this.messages = store.state.messages;
        },

    }
}
</script>
<style>
    .userlist {
        float: left;
        width: 15%;
        height: inherit;
        background-color: #4ca197;
        /*border: blue solid;*/
    }
    .row {
        margin: 0;
    }
    .justify {
        justify-content: center;
    }
    /*region chatbox*/
    .chatbox {
        /*border: orange solid;*/
        background-color: #4ca197;
        height: inherit;
    }
    .messagelist {
        padding: 5px;
        height: 85vh;
        min-height: 85vh;
        max-height: 85vh;
        /*border: magenta solid;*/
        overflow: auto;
    }

    .messagebox {
        margin-bottom: 10px;
        background-color: #35857b;
        /*border: black solid;*/
    }
    .sendbox {
        /*border: yellow solid;*/
    }
    /*endregion*/

    .radius {
        border-radius: 13px;
    }
    .deletemess > img {
        width: 10px;
        float: left;
    }
    .deletemess {
        background: 0;
        border: 0;
        float: right;
    }

    .ta-right {
        text-align: right;
    }

</style>