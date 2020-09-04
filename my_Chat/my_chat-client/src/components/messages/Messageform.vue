<template>
    <section class="senderform">
        <form @submit.stop.prevent="sendmessage" method="post" class="row">
            <textarea class="Messagebar mr-3 col-11 mb-2" placeholder="Write your message here" name="contenu" v-model="contenu" required></textarea>
            <button type="submit" value="Add Message" class="sendbutton"><img src="../../assets/send.png"></button>
        </form>
    </section>
</template>

<script>
import axios from 'axios';
import store from "../../store/store";
export default {
    data() {
        return {
            contenu: '',
        }
    },
    methods: {
        sendmessage() {
            let store = this.$store;
            axios.post('http://localhost:8000/api/messages/add', {
                user_id: store.state.user.id,
                contenu: this.contenu,
                channel_id: store.state.currentchannel,
            })
            .then(response => {
                console.log(response);
                this.getPrivate(store.state.currentchannel);
                this.contenu = "";
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getPrivate(channel_id) {
            store.commit( "setChannel", channel_id);
            console.log("Current channel is : " + channel_id);
            axios.get('http://localhost:8000/api/channels/' + channel_id)
                .then(response => {
                    store.state.messages = response.data;
                    this.$emit('selectChannel');
                });
        },
    }
}
</script>

<style>
    .senderform {
        margin: 5px 0;
    }
    .Messagebar {
        height: 40px;
        padding: .5em .8em;
        border: 1px solid #ccc;
        -webkit-box-shadow: inset 0 1px 3px #ddd;
        box-shadow: inset 0 1px 3px #ddd;
    }
    .Messagebar:focus {
        outline: 0;
        border-color: #129fea;
    }
    textarea {
        resize: none;
        border-radius: 10px;
    }
    .sendbutton {
        padding: 5px;
        border-radius: 20px 50px 50px;
    }
    .sendbutton > img {
        width: 25px;
        padding-left: 3px;
    }
</style>