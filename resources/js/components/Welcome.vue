<template>
    <div>

        <div class="section">
            <h1 class="title has-text-centered">
                TRAINING INFORMATION CENTER MANAGEMENT SYSTEM (TICMS)
            </h1>
            <p class="subtitle has-text-centered">Online Reservation of DICT Training Center</p>
        </div>

        <set-appointment-component v-if="isAuth === 1"></set-appointment-component>


    </div>
</template>

<script>
export default {
    props: ['propIsAuth'],
    data() {
        return {}
    },
    methods: {},
    mounted() {

    },

    computed: {
        isAuth(){
            return parseInt(this.propIsAuth)
        }
    }
}
</script>
<style scoped>

</style>
