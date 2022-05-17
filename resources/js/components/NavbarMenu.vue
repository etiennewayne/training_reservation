<template>
    <div>
        <b-navbar>
            <template #brand>
                <img class ="logo"
                     src="/img/gadtclogo.png" style="height: 100px;">
                <img class ="logo"
                     src="/img/dict_logo.png" style="height: 90px;">
            </template>

            <template #start>
            </template>

            <template #end>
                <b-navbar-item href="/">
                    Home
                </b-navbar-item>
                <b-navbar-item href="/covid-updates">
                    Covid Updates
                </b-navbar-item>
                <b-navbar-item tag="div">
                    <div class="buttons" v-if="!currentLogin">
                        <a class="button is-primary" href="/sign-up">
                            <strong>Sign up</strong>
                        </a>
                        <a class="button is-light" @click="isModalActive = true">
                            Log in
                        </a>
                    </div>
                    <div class="buttons" v-else>

                        <a v-if="user.role === 'USER'" class="button is-link" href="/dashboard-user">
                            DASHBOARD
                        </a>
                        <a v-if="user.role === 'OFFICE'" class="button is-link" href="/dashboard-office">
                            DASHBOARD
                        </a>
                    </div>
                </b-navbar-item>
            </template>
        </b-navbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
        }
    },
    methods: {

        initData: function(){
            if(this.propUser){
                this.user = JSON.parse(this.propUser);
            }
        }

    },
    mounted() {
        this.initData();
    },

    computed: {
        showName(){
            if(this.user){
                return this.user.fname.toUpperCase();
            }
            return '';
        },
        currentLogin(){
            if(this.user){
                return true;
            }
            return false;
        }
    }
}
</script>
<style scoped>

</style>
