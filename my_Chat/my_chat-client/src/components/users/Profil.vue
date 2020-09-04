<template>
    <section class="body-box row">
        <div class="profilbox radius col-4">
            <img class="profilavatar radius" src="../../assets/image-profil.png" alt="avatar">
            <div>
                <div v-if="user != null" class="profilname">Profil de <b>{{user.username}}</b></div>
            </div>
        </div>
    </section>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                user: null
            }
        },
        props: ['id'],
        mounted() {
            let url = 'http://localhost:8000/users/' + this.id.toString();
            axios.get(url,)
                .then(response => this.user = response.data);
        },
        methods: {

        }
    }
</script>

<style scoped>
    .profilbox {
        background-color: #4ca197;
        margin-left: 33%;
        margin-top: 15%;
        padding: 75px;
    }
    .profilavatar {
        margin-left: 47%;
        width: 25px;
        border: black solid 1px;
    }
    .profilname {
        text-align: center;
    }
</style>