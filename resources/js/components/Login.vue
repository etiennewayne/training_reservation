<template>
    <div class="login-wrapper">
        <div class="login">
            <form @submit.prevent="submit">
                <div class="panel">
                    <div class="panel-heading">
                        SECURITY CHECK
                    </div>

                    <div class="panel-body">
                        <b-field label="Username" label-position="on-border">
                            <b-input type="text" v-model="fields.username" placeholder="Username" />
                        </b-field>

                        <b-field label="Password" label-position="on-border">
                            <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" />
                        </b-field>

                        <div class="buttons">
                            <button class="button is-success">LOGIN</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            fields: {
                username: '',
                password: '',
            },
        }
    },

    methods: {
        submit: function(){
            axios.post('/login', this.fields).then(res=>{
                console.log(res.data)
                if(res.data.role === 'USER'){
                    window.location = '/';
                }
                if(res.data.role === 'ADMINISTRATOR'){
                    window.location = '/admin-dashboard';
                }
               // window.location = '/dashboard';
            })
        }
    }
}
</script>


<style scoped>
    .login-wrapper{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login{
        width: 500px;
    }

    .panel-body{
        padding: 25px;
    }



</style>
