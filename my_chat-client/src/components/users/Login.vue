<template>
    <section class="body-box row">
        <div class="authbox radius">
            <form @submit.stop.prevent="login" method="POST">
                <legend class="center mt-1">Login</legend>
                <fieldset class="authfieldset">
                    <label>Username</label>
                    <input type="text" placeholder="Username" name="username" v-model="username" required>

                    <label>Password</label>
                    <input type="password" placeholder="Password" name="password" v-model="password" required>

                    <div class="bouton"><button type="submit" value="Add User" class="mt-2">Sign up</button></div>
                    <router-link to="/register">Create an account here!</router-link>
                </fieldset>
            </form>
        </div>
    </section>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                username: '',
                password: '',
            }
        },
        methods: {
            login() {
                let store = this.$store;
                axios.post('http://localhost:8000/api/login', {
                    username: this.username,
                    password: this.password,
                })
                // .then(response => state.user = response.data)
                .then(response => {
                    store.commit("setUser", response.data)
                    store.commit("setAuthenticated", true)
                    this.redirect();
                })
                .catch(function (error) {
                    console.log(error);
                    store.commit("setAuthenticated", false)
                });

            },
            redirect() {
                this.$router.push("/messages");
            }
        }
    }
</script>

<style scoped>
    .pure-button {
        border-radius: 5px;
    }
    .authbox {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #4ca197;
        padding: 20px;
        width: 350px;
        font-size: 21px;
        font-family: 'PT Sans', sans-serif;
    }
    .center {
        text-align: center;
    }
    .authfieldset {
        margin: auto;
        width: min-content;
    }
    .bouton {
        text-align: center;
    }
</style>