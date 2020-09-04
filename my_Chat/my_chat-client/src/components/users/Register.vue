<template>
    <section class="body-box row">
        <div class="col-3 authbox radius">
            <form @submit.stop.prevent="register" method="POST" class="pure-form pure-form-stacked">
                <legend class="center mt-1">Register</legend>
                <fieldset class="authfieldset">
                    <label>Username</label>
                    <input type="text" placeholder="Username" name="username" v-model="username" required>

                    <label>Password</label>
                    <input type="password" placeholder="Password" name="password" v-model="password" required>

                    <button type="submit" value="Add User" class="pure-button pure-button-primary mt-2">Register</button><br>
                    <router-link to="/login" class="">Already registered? Sign up here!</router-link>
                </fieldset>
            </form>
        </div>
    </section>
</template>

<script>
    import axios from "axios";
    export default {
        data() {
            return {
                username: '',
                password: ''
            }
        },
        methods: {
            register() {
                axios.post('http://localhost:8000/api/register', {
                    username: this.username,
                    password: this.password,
                })
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    }
</script>

<style scoped>
    .pure-button {
        border-radius: 5px;
    }
    .authbox {
        margin-left: 37%;
        margin-top: 15%;
        background-color: #4ca197;
    }
    .center {
        text-align: center;
    }
    .authfieldset {
        text-align: center;
        margin-left: 30%;
        width: min-content;
    }
</style>