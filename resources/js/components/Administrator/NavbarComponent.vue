<template>
    <div>
        <div class="mynav">
            <div class="mynav-brand">{{ userRole }}</div>
            <div class="burger-button" @click="open = true">
                <div class="burger-div"></div>
                <div class="burger-div"></div>
                <div class="burger-div"></div>
            </div>
        </div>

            <b-sidebar
                type="is-light"
                :fullheight="fullheight"
                :fullwidth="fullwidth"
                :overlay="overlay"
                :right="right"
                v-model="open">
                <div class="p-4">
                    <h3 class="title is-4">{{ userRole }}</h3>
                    <b-menu>

                        <b-menu-list label="Menu">

                            <b-menu-item icon="information-outline" label="Dashboard" tag="a" href="/cpanel"></b-menu-item>

                            <b-menu-item label="Office" icon="domain" tag="a" href="/offices"></b-menu-item>

                            <b-menu-item label="Training Center" icon="domain" tag="a" href="/training-centers"></b-menu-item>

                            <b-menu-item label="Appointment" icon="calendar-month-outline" tag="a" href="/appointments"></b-menu-item>

                            <b-menu-item label="User" icon="account" tag="a" href="/users"></b-menu-item>
                        </b-menu-list>

                        <b-menu-list label="Actions">
                            <b-menu-item @click="logout" label="Logout"></b-menu-item>
                        </b-menu-list>
                    </b-menu>
                </div>
            </b-sidebar>

    </div>


</template>

<script>
export default {
    data(){
        return{
            open: false,
            overlay: true,
            fullheight: true,
            fullwidth: false,
            right: true,

            user: {
                role: '',
                lname: '',
                fname: '',
                mname: '',
            },
        }
    },
    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        },

        initData(){
            axios.get('/init-user').then(res=>{
                this.user = res.data;
            });
        },
    },

    mounted(){
        this.initData();
    },

    computed: {

        userRole(){
            return this.user.role.toUpperCase();
        }
    }
}
</script>

<style scoped>
    .logo{
        padding: 0 30px 0 30px;
        height: 90px;
    }

    .burger-div{
        width: 20px;
        height: 3px;
        background-color: #696969;
        margin: 0 0 3px 0;
        margin-left: auto;
        border-radius: 10px;
    }

    .burger-button{
        display: flex;
        flex-direction: column;
        margin-left: auto;
    }

    .mynav{
        padding: 25px;
        border-bottom: 2px solid rgba(22, 69, 28, 0.53);
        display: flex;
    }

    .mynav-brand{
        font-weight: bold;
        font-size: 1.2em;
    }

  /* .hero{
        background-image: url("/img/bg-hero.jpg");
        background-repeat: no-repeat;
        background-size: cover;
    } */

</style>
