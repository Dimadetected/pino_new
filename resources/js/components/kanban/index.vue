<template>
    <div class="col-12 mt-5">
        <div class="row mt-5">
            <div class="col-md-1">

            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Начало периода</label>
                            <input type="text" class="form-control" v-model="date_start" @change="getColumns"
                                   placeholder="дд.мм.гггг">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Окончание периода</label>
                            <input type="text" class="form-control" v-model="date_end" @change="getColumns"
                                   placeholder="дд.мм.гггг">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="">&nbsp;</label>
                        <button class="btn btn-block btn-primary" @click="typeShowTextSwap">{{ typeShowText }}</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Заказчик</label>
                            <select class="form-control" @change="getColumns" v-model="client_id">
                                <option value="all">Все</option>
                                <option v-for="master in masters" :value="master.id">{{ master.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Отвественный</label>
                            <select class="form-control" @change="getColumns" v-model="master_id">
                                <option value="all">Все</option>
                                <option v-for="master in masters" :value="master.id">{{ master.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Исполнитель</label>
                            <select class="form-control" @change="getColumns" v-model="worker_id">
                                <option value="all">Все</option>
                                <option v-for="master in masters" :value="master.id">{{ master.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">

            <div v-if="typeShowText == 'Таблица'" class="col-12">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-2" v-for="(column,index) in columnsArr">
                        <div class="p-2 alert " :class="alertsArr[index]">
                            <h3>{{ column.text }} {{ alertsArr.shift }}</h3>
                            <!-- Backlog draggable component. Pass arrBackLog to list prop -->
                            <draggable
                                class="list-group kanban-column"
                                :list="column.tasks"
                                group="tasks"
                                @change="log"
                            >
                                <div @click="viewModal(task)"
                                     class="list-group-item"
                                     v-for="task in column.tasks"
                                     :key="task.name"
                                >
                                    <div class="pb-2 border-bottom">#{{task.id}} <br>{{ task.name }}</div>
                                    <div style="font-size: 10px">
                                        <table class="table table-borderless mt-1">
                                            <tbody>
                                            <tr>
                                                <td class="p-0">Заказчик:</td>
                                                <td class="p-0">{{ task.user }}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Ответственный:</td>
                                                <td class="p-0">{{ task.master }}</td>
                                            </tr>
                                            <tr v-if="task.worker !== null">
                                                <td class="p-0">Исполнитель:</td>
                                                <td class="p-0">{{ task.worker }}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">Осталось:</td>
                                                <td class="p-0">{{ task.date }}ч.</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </draggable>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="col-md-10 offset-md-1">
                <table class="table table-bordered " v-for="(column,index) in columnsArr"
                       v-if="column.tasks.length > 0">
                    <thead>
                    <th>Задача</th>
                    <th>Заказчик</th>
                    <th>Ответственный</th>
                    <th>Исполнитель</th>
                    <th>Статус</th>
                    <th>Дата постановки</th>
                    <th>Дата выполнения</th>
                    </thead>
                    <tbody>
                    <tr :class="column.id === 1? 'table-secondary': (column.id === 2? 'table-primary':(column.id === 3?'table-warning':(column.id === 4?'table-success':'table-danger')))"
                        v-for="task in column.tasks">
                        <td class="p-2">#{{task.id}} {{ task.name }}</td>
                        <td class="p-2">{{ task.user }}</td>
                        <td class="p-2">{{ task.master }}</td>
                        <td class="p-2">{{ task.worker }}</td>
                        <td class="p-2">{{ column.text }}</td>
                        <td class="p-2">{{ task.date_created }}</td>
                        <td class="p-2">{{ task.date_closed }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <modal name="example" height="70%" width="80%" styles="overflow-y: hidden;max-height:70%;">
            <div class="col-12 p-md-5 p-3">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <h3>{{ modalObject.name }}</h3>
                        <hr>
                        <p class="lead mt-4">
                            {{ modalObject.text }}
                        </p>
                        <div class="row mt-5">
                            <div class="col-md-6 text-primary">Заказчик: {{ modalObject.user }}</div>
                            <div class="col-md-6 text-right text-success">Ответственный: {{ modalObject.master }}</div>
                            <div class="col-md-6 ">Исполнитель: {{ modalObject.worker }}</div>
                            <div class="col-md-6 text-right text-danger">Осталось: {{ modalObject.date }}ч.</div>
                        </div>
                        <div class="row mt-3"
                             v-if="(user_id == modalObject.user_id) || (user_id == modalObject.master_id)">
                            <div class="col-12 text-right">
                                <a :href="'/kanban/form/' + modalObject.id" class="btn btn-warning">Редактировать</a>
                                <a :href="'/kanban/destroy/' + modalObject.id" class="btn btn-danger">Удалить</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-5 mt-md-0" style="min-height: 100%">
                        <info-modal :user_id="user_id" :id="modalObject.id"></info-modal>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        components: {
            draggable,
        },
        props: {
            user_id: Number,
        },
        data() {
            return {
                typeShowText: "Таблица",
                load: false,
                date_start: "",
                date_end: "",
                masters: {},
                master_id: 0,
                worker_id: 0,
                client_id: 0,
                modalObject: {},
                alertsArr: {
                    0: 'alert-secondary',
                    1: 'alert-primary',
                    2: 'alert-warning',
                    3: 'alert-success',
                    4: 'alert-danger'
                },
                columnsArr: [],
                // for new tasks
                newTask: "",
                // 4 arrays to keep track of our 4 statuses
            };
        },
        watch: {
            date_start(new_date) {
                localStorage.date_start = new_date
            },
            date_end(new_date) {
                localStorage.date_end = new_date
            },
            client_id(value) {
                localStorage.client_id = value
            },
            master_id(value) {
                localStorage.master_id = value
            },
            worker_id(value) {
                localStorage.worker_id = value
            },
        },
        methods: {
            lsFill() {
                if (localStorage.date_start) {
                    this.date_start = localStorage.date_start
                }
                if (localStorage.date_end) {
                    this.date_end = localStorage.date_end
                }
                if (localStorage.client_id) {
                    this.client_id = localStorage.client_id
                }
                if (localStorage.master_id) {
                    this.master_id = localStorage.master_id
                }
                if (localStorage.worker_id) {
                    this.worker_id = localStorage.worker_id
                }
            },
            typeShowTextSwap() {
                if (this.typeShowText == "Таблица") {
                    this.typeShowText = "Столбцы"
                } else {
                    this.typeShowText = "Таблица"
                }
                console.log(this.typeShowText)
            },
            //add new tasks method
            add() {
                if (this.newTask) {
                    this.arrBackLog.push({name: this.newTask});
                    this.newTask = "";
                }
            },
            getColumns() {
                this.load = false
                fetch('/api/kanban_columns?' + new URLSearchParams({
                    user_id: this.user_id,
                    master_id: (this.master_id === 'all' ? 0 : this.master_id),
                    worker_id: (this.worker_id === 'all' ? 0 : this.worker_id),
                    client_id: (this.client_id === 'all' ? 0 : this.client_id),
                    date_start: this.date_start,
                    date_end: this.date_end,
                }))
                    .then(res => res.json())
                    .then(res => {
                        this.columnsArr = res.data;
                        console.log(this.columnsArr)
                        this.load = true
                    })
            },
            getMasters() {
                fetch('/api/users')
                    .then(res => res.json())
                    .then(res => {
                        this.masters = res.data;
                    })
            },
            log(evt) {
                window.console.log(evt);
                window.console.log(this.columnsArr);
                this.save();
            },
            save() {
                fetch(
                    '/api/kanban_tasks/allTasksChange',
                    {
                        method: 'POST',
                        body: JSON.stringify(
                            {
                                columns: this.columnsArr,
                                user_id: this.user_id,

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
                            // console.log(r.data);
                        }
                    )
            },
            viewModal(task) {
                this.modalObject = task;
                this.$modal.show('example')
            },
        },
        mounted() {
            this.lsFill()
            this.master_id = this.user_id;

            this.getColumns();
            this.getMasters();

        },
    }
</script>

<style scoped>

</style>
