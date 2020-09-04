<template>
    <section>
        <header class="mb-3">
            <nav role="navigation" aria-label="main navigation" class="navbar navbar-expand-sm bg-dark navbar-dark">
                <ul class="navbar-nav mr-auto">
                    <router-link to="/messages" v-if='authenticated' class="nav-link"> Messages </router-link>
                </ul>
                <ul class="navbar-nav">
                    <div v-if='!authenticated' class="navbar-nav">
                    <router-link to="/login" class="nav-link"> Login </router-link>
                    <router-link to="/register" class="nav-link"> Register </router-link>
                    </div>
                    <div v-if="authenticated" class="nav-link">{{username}}</div>
                    <a v-if="authenticated" class="nav-link">logout</a>
                </ul>
            </nav>
        </header>
        <div>
            <router-view></router-view>
        </div>
    </section>
</template>

<script>

export default {
    data() {
        return {
            authenticated : false,
            username : "",
        }
    },
    created() {
        this.authenticated = this.$store.state.authenticated
        this.username = this.$store.state.user.username
    },
    updated() {
        this.authenticated = this.$store.state.authenticated
        this.username = this.$store.state.user.username
    },

}
</script>

<style>
    .bg {
        background-color: #5fb8ae;
    }
    .body-box {
        background-color: #5fb8ae;
    }
    .float-r {
        float: right;
    }

</style>