<template>
    <div class="col-12">
        <div class="row">
            <div class="col-12 alert alert-success" v-if="alert">
                Данные были успешно обновлены
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Название цепочки:
                    </div>
                    <div class="col-md-8">
                        <input type="text" v-model="chain_name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Организация:
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" v-model="organisation_chain">
                            <option :value="organisation.id" v-for="organisation in organisations">
                                {{ organisation.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Признак:
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" v-model="type_chain">
                            <option :value="tc.id" v-for="tc in type_chains">
                                {{ tc.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 card card-body shadow my-2" v-for="(block,index) in blocks">
                <div class="row">
                    <div class="col-md-4">
                        Роль пользователя:
                    </div>
                    <div class="col-md-7">
                        <select class="form-control" @change="completeOption(index)" v-model="selects[index]">
                            <option :value="role.id" v-for="role in user_roles">{{ role.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger" @click="blockRemove(selects[index])">
                            X
                        </button>
                    </div>
                </div>
            </div>
            <button class="col-12 btn btn-success my-2" @click="blockAdd">Добавить</button>
            <button class="col-12 btn btn-primary" @click="save">Сохранить</button>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getUserRoles();
        if (this.chain !== 0)
            this.get();

        this.getOrganisations();
    },
    props: {
        chain: {
            type: Number,
            default() {
                return 0
            }
        }
    },
    data() {
        return {
            alert: false,
            type_chain: 1,
            type_chains: [
                {
                    "id" : 1,
                    "name": "Счет"
                },{
                    "id": 2,
                    "name": "Заявка"
                }
            ],
            organisations: [],
            organisation_chain: 0,
            chain_name: '',
            selects: [],
            blocks: [
                {
                    value: '',
                },
            ],
            user_roles: {},
        }
    },
    watch: {},
    methods: {
        getOrganisations() {
            fetch('/api/organisations')
                .then(res => res.json())
                .then(res => {
                    this.organisations = res.data
                })
        },
        get() {
            fetch('/api/chains/' + this.chain)
                .then(res => res.json())
                .then(res => {
                    this.blocks = [];
                    let i = 0;
                    this.chain_name = res.data.name
                    this.type_chain = res.data.type
                    res.data.value.map(chain => {
                        this.blocks.push({value: chain})
                        this.selects[i] = chain;
                        i += 1
                    });

                    this.organisation_chain = res.data.organisation_id;
                })
        },
        save() {
            let arrToSend = [];
            this.blocks.map(block => {
                arrToSend.push(block.value)
            });
            fetch(
                '/api/chains' + (this.chain == 0 ? '' : '/' + this.chain),
                {
                    method: this.chain == 0 ? 'POST' : 'PUT',
                    body: JSON.stringify(
                        {
                            token: 'base64:vfhjJc51xw1vWIRyH+JG36Xux3OP/IAAbOVQi2cjA0c=',
                            name: this.chain_name,
                            organisation_id: this.organisation_chain,
                            value: arrToSend,
                            type: this.type_chain,
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
                        console.log(r.data);
                        this.alert = true;
                    }
                )
        },
        completeOption(index) {
            this.blocks[index].value = this.selects[index]
        }
        ,
        custom_log(value) {
            console.log(value)
        }
        ,

        blockRemove(role) {
            this.blocks = this.blocks.filter(block => role !== block.value)
            let i = 0;
            this.selects = [];
            this.blocks.map(chain => {
                this.selects[i] = chain.value;
                i += 1
            });
        }
        ,
        blockAdd() {
            this.blocks.push({
                'role': false
            });
        }
        ,
        getUserRoles() {
            fetch('/api/user_roles')
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    this.user_roles = res.data
                });
        }
    }
}
</script>

<style scoped>

</style>
