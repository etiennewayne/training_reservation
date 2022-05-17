<template>
    <div>

        <navbar-menu></navbar-menu>

        <section class="section">
            <div class="columns">
                <div class="column is-6 is-offset-3">
                    <div class="time-container">
                        <form @submit.prevent="submitAppointment">
                            <div class="p-2">
                                <h1 class="title is-4 mb-4">SET AN APPOINTMENT NOW</h1>
                                <b-field label="SELECT DATE" grouped  expanded class="is-centered" label-position="on-border">
                                    <b-datetimepicker rounded expanded
                                              v-model="appointment.appointment_date"
                                              placeholder="Type or select a date..."
                                              icon="calendar-today"
                                              :locale="locale"
                                              editable>
                                    </b-datetimepicker>
                                </b-field>
                                <b-field label="TRAINING CENTER" expanded label-position="on-border"
                                         :type="errors.training_center ? 'is-danger' : ''"
                                         :message="errors.training_center ? errors.training_center[0] : ''">
                                    <b-select v-model="appointment.training_center" expanded rounded>
                                        <option v-for="(item, index) in trainingCenters" :key="index" :value="item.traning_center_id">{{ item.training_center }}</option>
                                    </b-select>
                                </b-field>


                                <b-notification v-if="this.errors.not_allowed"
                                        type="is-danger is-light"
                                        aria-close-label="Close notification"
                                        role="alert">
                                    {{ this.errors.not_allowed[0] }}
                                </b-notification>

                                <b-notification v-if="this.errors.limit"
                                                type="is-danger is-light"
                                                aria-close-label="Close notification"
                                                role="alert">
                                    {{ this.errors.limit[0] }}
                                </b-notification>

                                <div class="buttons is-right">
                                    <button class="button is-primary is-rounded">SET APPOINTMENT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>



        <footer-page></footer-page>





        <!--modal-->
        <b-modal v-model="isModalActive" has-modal-card
                 trap-focus width="640" aria-role="dialog" aria-modal>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Login</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false"/>
                </header>

                <section class="modal-card-body">
                    <div>
                        <form @submit.prevent="submit">
                            <b-field label="Username"
                                 label-position="on-border"
                                 :type="errors.username ? 'is-danger' : ''"
                                 :message="errors.username ? errors.username : ''">
                            <b-input type="text" v-model="fields.username" placeholder="Username" expanded auto-focus></b-input>
                            </b-field>

                            <b-field label="Password" label-position="on-border"
                                    :type="errors.password ? 'is-danger' : ''"
                                    :message="errors.password ? errors.password : ''">
                                <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" expanded auto-focus></b-input>
                            </b-field>

                            <footer class="modal-card-foot">
                                <button
                                    class="button is-success"
                                    label="LOGIN"
                                    type="is-success">LOGIN</button>

                                <b-button
                                    label="Close"
                                    @click="isModalActive=false"></b-button>
                            </footer>
                        </form>
                    </div>
                </section>

            </div>
        </b-modal>

    </div> <!--root div-->
</template>

<script>
import FooterPage from "./FooterPage";
export default {
    components: {FooterPage},
    props: ['propUser'],
    data(){
        return{
            locale: undefined,
            modalHealDeclaration: false,
            isModalActive: false,
            fields: {},
            errors: {},

            trainingCenters: '',


            appointment: {
                appointment_date: null,
                nAppointmentDate: '',
                appointment_type: '',
            },
        }

    },

    methods: {
        submit: function(){

            axios.post('/login', this.fields).then(res=>{
                console.log(res.data);
               if(res.data.role === 'ADMINISTRATOR'){
                    window.location = '/dashboard-admin';
               }else if(res.data.role === 'USER'){
                  // window.location = '/dashboard-user';
                   window.location = '/';
               }else if(res.data.role === 'OFFICE'){
                   window.location = '/dashboard-office';
               }
            }).catch(err => {
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });
        },

        loadTrainingCenters(){
            axios.get('/get-open-training-centers')
            .then(res=>{
                this.trainingCenters = res.data;
            });
        },

        submitAppointment: function() {
            //before proceed, health declaration first
            this.modalHealDeclaration = true;
            // axios.get('/get-healt-questions').then(res => {
            //     this.health_questions = res.data;
            // })
        },

        submitAppointmentNow(){

            if (Object.values(this.declarataions).indexOf('1') > -1) {
                this.$buefy.toast.open({
                    message: 'Sorry, you cannot proceed with the appointment.',
                    type: 'is-danger'
                })
                this.modalHealDeclaration = false;
                return false;
            }
            var size = Object.keys(this.declarataions).length;
            if(size < 10){
                this.$buefy.toast.open({
                    message: 'Please complete the health declaration.',
                    type: 'is-danger'
                })
                return false;
            }


            this.modalHealDeclaration = false;
            this.appointment.app_date = new Date(this.appointment.appointment_date).toLocaleDateString();
            this.appointment.app_time = new Date(this.appointment.appointment_date).toLocaleTimeString();

            axios.post('/my-appointment', this.appointment).then(res=>{

                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'APPOINTMENT SAVED!',
                        message: 'Appointment saved.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.appointment = {};
                            this.errors = {};
                        }
                    });
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
                if(err.response.status === 401){
                    this.isModalActive = true;
                }
            });
        },



    },

    mounted() {

        this.loadTrainingCenters();
    },


}
</script>

<style scoped>

    .time-container{
        position: relative;

        z-index: 1;
        padding: 15px;
        background: white;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }



    @media only screen and (max-width: 1024px) {

    }



</style>
