<template>
    <section>
        <div>
            <button class="test text-dark" v-on:click="home">Acceuil</button>
        </div>
        <div v-for="user in users" v-bind:key="user.id" class="user">
<!--            <router-link class="nav-link text-dark" :to="'/profil/'+user.id">{{ user.username }}</router-link>-->
            <button class="test text-dark" v-on:click="getPrivate(user.channel_id)">{{ user.username }}</button>
        </div>
    </section>
</template>

<script>
    import axios from 'axios'
    import store from "../../store/store";

    export default {
        data() {
            return {
                users: [],
            }
        },
        created() {
            axios.post('http://localhost:8000/api/channels/users', {
                current: store.state.user.username
            })
            .then(response => this.users = response.data)
        },
        methods: {
            getPrivate(channel_id) {
                store.commit( "setChannel", channel_id);
                console.log("Current channel is : " + channel_id);
                axios.get('http://localhost:8000/api/channels/' + channel_id)
                .then(response => {
                    store.state.messages = response.data;
                    this.$emit('selectChannel');
                });
            },
            home(){
                let store = this.$store;
                store.commit("setMessages", []);
                this.$emit('selectChannel');
            }
        }
    }
</script>

<!--let store = this.$store;
let channel = store.state.currentchannel;

if (store.state.currentchannel !== 0) {
console.log("COU??");
axios.get('http://localhost:8000/api/channels/' + channel)
.then(response => this.messages = response.data);
}
console.log("CAN YOU PLEASE UPDATE??");-->

