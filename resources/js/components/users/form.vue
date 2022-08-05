<template>
    <div class="col-12">
        <div class="row">
            <div class="col-12 alert alert-success" v-if="alert">
                Данные были успешно обновлены
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        ФИО:
                    </div>
                    <div class="col-md-8">
                        <input type="text" v-model="user_name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Email:
                    </div>
                    <div class="col-md-8">
                        <input type="text" v-model="user_email" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Телефон:
                    </div>
                    <div class="col-md-8">
                        <input type="text" v-model="phone" placeholder="Формат: 70000000000" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Внешний сотрудник:
                    </div>
                    <div class="col-md-8">
                        <input type="checkbox" v-model="inner" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Роль:
                    </div>
                    <div class="col-md-8">
                        <select v-model="user_role" class="form-control">
                            <option v-for="one_role in user_roles" :value="one_role.id">{{ one_role.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Организации:
                    </div>
                    <div class="col-md-8">
                        <button class="mr-1 btn my-2" v-for="organisation in organisations"
                                :class="selected_organisations.indexOf(organisation.id) === -1?'btn-secondary':'btn-primary'"
                                @click="changeSelect(organisation.id)">
                            {{ organisation.name }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Уведомления:
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="tg_notice"
                                           id="tg_notice">
                                    <label class="form-check-label" for="tg_notice">
                                        Telegram
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="email_notice"
                                           id="email_notice">
                                    <label class="form-check-label" for="email_notice">
                                        Email
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="sms_notice"
                                           id="sms_notice">
                                    <label class="form-check-label" for="sms_notice">
                                        СМС
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="col-12 btn btn-primary" @click="save">Сохранить</button>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getUserRoles();
        if (this.user !== 0)
            this.get();
        this.getOrganisations()
    },
    props: {
        user: {
            type: Number,
            default() {
                return 0
            }
        }
    },
    data() {
        return {
            alert: false,
            user_name: '',
            phone: '',
            inner: false,
            user_email: '',
            user_role: '',
            user_roles: [],
            tg_notice: false,
            email_notice: false,
            sms_notice: false,
            organisations: [],
            selected_organisations: [],
        }
    },
    watch: {},
    methods: {
        changeSelect(id) {
            if (this.selected_organisations.indexOf(id) === -1)
                this.selected_organisations.push(id)
            else
                this.selected_organisations.splice(this.selected_organisations.indexOf(id), 1)

        },
        getOrganisations() {
            fetch('/api/organisations')
                .then(res => res.json())
                .then(res => {
                    this.organisations = res.data
                });
        },
        getUserRoles() {
            fetch('/api/user_roles')
                .then(res => res.json())
                .then(res => {
                    this.user_roles = res.data
                });
        },
        get() {
            fetch('/api/users/' + this.user)
                .then(res => res.json())
                .then(res => {
                    this.user_name = res.data.name
                    this.user_email = res.data.email
                    this.phone = res.data.phone
                    this.user_role = res.data.user_role.id
                    this.tg_notice = res.data.tg_notice
                    this.email_notice = res.data.email_notice
                    this.sms_notice = res.data.sms_notice
                    if (res.data.inner === 1){
                        this.inner = true
                    }
                    res.data.organisations.map(org => {
                        this.changeSelect(org.id)
                    })
                })
        },
        save() {
            fetch(
                '/api/users' + (this.user == 0 ? '' : '/' + this.user),
                {
                    method: this.user == 0 ? 'POST' : 'PUT',
                    body: JSON.stringify(
                        {
                            token: 'base64:vfhjJc51xw1vWIRyH+JG36Xux3OP/IAAbOVQi2cjA0c=',
                            name: this.user_name,
                            email: this.user_email,
                            user_role_id: this.user_role,
                            organisations: this.selected_organisations,
                            tg_notice: this.tg_notice,
                            email_notice: this.email_notice,
                            sms_notice: this.sms_notice,
                            inner: this.inner,
                            phone: this.phone,
                        }
                    ),
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }
            )
                .then(r => r.json())
                .then(r => {
                        this.data = r.data;
                        this.alert = true;
                    }
                )
        },
    }
}
</script>

<style scoped>

</style>
