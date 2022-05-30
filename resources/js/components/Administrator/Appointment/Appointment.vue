<template>
    <div>
        <div class="section">

            <div class="columns is-centered">
                <div class="column is-10">
                    <div class="box">
                        <div class="is-flex is-justify-content-center mb-2" style="font-size: 20px; font-weight: bold;">LIST OF APPOINTMENT</div>

                        <div class="level">
                            <div class="level-left">
                                <b-field label="Page">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                    <b-select v-model="sortOrder" @input="loadAsyncData">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>

                                    </b-select>
                                </b-field>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <b-field label="Search">
                                        <b-input type="text"
                                                 v-model="search.reference" placeholder="Search Reference"
                                                 @keyup.native.enter="loadAsyncData"/>
                                        <p class="control">
                                             <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                             </b-tooltip>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                        </div>


                        <div class="table-container">
                            <b-table
                                :data="data"
                                :loading="loading"
                                paginated
                                backend-pagination
                                :total="total"
                                :per-page="perPage"
                                @page-change="onPageChange"
                                aria-next-label="Next page"
                                aria-previous-label="Previous page"
                                aria-page-label="Page"
                                aria-current-label="Current page"
                                backend-sorting
                                :default-sort-direction="defaultSortDirection"
                                @sort="onSort">

                                <b-table-column field="appointment_id" label="ID" v-slot="props">
                                    {{ props.row.appointment_id }}
                                </b-table-column>

                                <b-table-column field="ref_no" label="Reference No." v-slot="props">
                                    {{ props.row.ref_no }}
                                </b-table-column>

                                <b-table-column field="training_center" label="Training Center" v-slot="props">
                                    {{ props.row.training_center.training_center }}
                                </b-table-column>

                                <b-table-column field="fullname" label="Appointee" v-slot="props">
                                    {{ props.row.user.lname }}, {{ props.row.user.fname }} {{ props.row.user.mname }}
                                </b-table-column>

                                <b-table-column field="app_date" label="Appointment Date" v-slot="props">
                                    {{ props.row.app_date }}
                                </b-table-column>

                                <b-table-column field="app_time" label="Appointment Time" v-slot="props">
                                    {{ props.row.app_time_from | formatTime }} - {{ props.row.app_time_to | formatTime }}
                                </b-table-column>

                                <b-table-column field="app_status" label="Remarks" v-slot="props">
                                    <span v-if="props.row.app_status === 0" class="pending">PENDING</span>
                                    <span v-else-if="props.row.app_status === 1" class="approved">APPROVED</span>
                                    <span v-else class="cancelled">CANCELLED</span>
                                </b-table-column>

                                <b-table-column field="remarks" label="Remarks" v-slot="props">
                                    {{ props.row.remarks }}
                                </b-table-column>

                                <b-table-column label="Action" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip label="Edit" type="is-warning">
                                            <b-button v-if="props.row.app_status === 0" class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="getData(props.row.appointment_id)"></b-button>
                                        </b-tooltip>

                                        <b-tooltip label="Approve" type="is-info">
                                            <b-button v-if="props.row.app_status === 0" class="button is-small is-info mr-1" icon-right="thumb-up-outline" @click="confirmApproveAppointment(props.row.appointment_id)"></b-button>
                                        </b-tooltip>
                                    </div>
                                </b-table-column>
                            </b-table>
                        </div> <!--table container -->

                        <div class="buttons mt-3">
                            <b-button @click="openModal" icon-right="account-arrow-up-outline" class="is-success">NEW</b-button>
                        </div>

                    </div>
                </div><!--close column-->
            </div>


        </div><!--section div-->



        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal
                type = "is-link">

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Appointment Type</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <b-field label="SELECT DATE" grouped  expanded class="is-centered" label-position="on-border"
                                     :type="this.errors.appointment ? 'is-danger':''"
                                     :message="this.errors.appointment ? this.errors.appointment[0] : ''">
                                <b-datepicker rounded expanded
                                        v-model="fields.appointment_date"
                                        placeholder="Type or select a date..."
                                        icon="calendar-today"
                                        :locale="locale"
                                        editable>
                                    </b-datepicker>
                            </b-field>

                            <div class="columns">
                                    <div class="column">
                                        <b-field label="SELECT TIME FROM" label-position="on-border">
                                            <b-timepicker
                                                rounded
                                                placeholder="From..."
                                                icon="clock"
                                                v-model="fields.appointment_time_from"
                                                :enable-seconds="false"
                                                editable
                                                :locale="locale">
                                            </b-timepicker>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="SELECT TIME TO" label-position="on-border">
                                            <b-timepicker
                                                rounded
                                                placeholder="From..."
                                                icon="clock"
                                                v-model="fields.appointment_time_to"
                                                :enable-seconds="false"
                                                editable
                                                :locale="locale">
                                            </b-timepicker>
                                        </b-field>
                                    </div>
                                </div>

                            <b-field label="TRAINING CENTER" expanded label-position="on-border"
                                    :type="errors.training_center ? 'is-danger' : ''"
                                    :message="errors.training_center ? errors.training_center[0] : ''">
                                <b-select v-model="fields.training_center" expanded rounded>
                                    <option v-for="(item, index) in trainingCenters" :key="index" :value="item.training_center_id">{{ item.training_center }}</option>
                                </b-select>
                            </b-field>


                            <b-field label="Remarks" expanded label-position="on-border">
                                <b-input type="textarea" v-model="fields.remarks" placeholder="Remarks"></b-input>
                            </b-field>


                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->

    </div>
</template>

<script>
export default {

    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'appointment_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            locale: undefined, // Browser locale

            global_id : 0,

            search: {
                reference: '',
            },

            isModalCreate: false,

            fields: {
                appointment_date: null,
                nAppointmentDate: '',
                appointment_type: '',
            },

           

            trainingCenters: [],

            errors: {},



            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

        }
    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `reference=${this.search.reference}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-appointments?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.clearFields();
        },


        clearFields(){
            this.fields = {
                appointment_date: null,
                nAppointmentDate: '',
                appointment_type: '',
            };
            this.errors = {};
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/appointments/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/appointments/' + data_id).then(res=>{
                this.fields.training_center = res.data.training_center_id;
                let dateNTime = res.data.app_date;

                console.log(res.data);
                this.fields.appointment_time_from = new Date("2020-11-21 " + res.data.app_time_from); 
                this.fields.appointment_time_to = new Date("2020-11-21 "+ res.data.app_time_to);
                //adding constant date fo the trick converting string time to time


                this.fields.appointment_date = new Date(dateNTime);
                this.fields.remarks = res.data.remarks;

            });
        },

        submit: function(){

            this.fields.app_date = new Date(this.fields.appointment_date).toLocaleDateString();
            //this.fields.app_time = new Date(this.fields.appointment_date).toLocaleTimeString();
            this.fields.app_time_from = new Date(this.fields.appointment_time_from).toLocaleTimeString();
            this.fields.app_time_to = new Date(this.fields.appointment_time_to).toLocaleTimeString();

            if(this.global_id > 0){
                //update
                axios.put('/appointments/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/appointments', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });
            }
        },


        confirmApproveAppointment(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'APPROVED!',
                type: 'is-info',
                message: 'Are you sure you want to approve this appointment?',
                cancelText: 'Cancel',
                confirmText: 'Approve',
                onConfirm: () => this.approvedAppointment(delete_id)
            });
        },
        approvedAppointment(dataId){
            axios.post('/appointment-approved/' + dataId).then(res=>{
                if(res.data.status === 'approved'){
                    this.$buefy.dialog.alert({
                        title: 'APPROVED!',
                        message: 'Successfully approved.',
                        type: 'is-success',
                        confirmText: 'OK',
                        onConfirm: () => {
                            this.loadAsyncData();
                            this.clearFields();
                            this.global_id = 0;
                        }
                    })
                }
            })
        },



        loadTrainingCenter(){
            axios.get('/get-open-training-centers').then(res=>{
                this.trainingCenters = res.data;
            })
        },

    },

    mounted() {

        this.loadAsyncData();
        this.loadTrainingCenter();
    }

}
</script>

<style scoped>
    .approved{
        font-weight: bold;
        color: green;
    }
    .cancelled{
        font-weight: bold;
        color: red;
    }

    .pending{
        font-weight: bold;
        color: #1a73bd;
    }
</style>
