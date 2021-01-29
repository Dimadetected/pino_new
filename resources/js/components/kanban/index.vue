<template>
    <div class="col-12 mt-5">
        <div class="row mt-5">
            <div class="col-1"></div>
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
                            <div class="pb-2 border-bottom">{{ task.name }}</div>
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
                            <div class="col-md-6 text-primary">Дал: {{ modalObject.user }}</div>
                            <div class="col-md-6 text-right text-success">Исполнитель: {{ modalObject.master }}</div>
                            <div class="col-12 text-right text-danger">Осталось: {{ modalObject.date }}ч.</div>
                        </div>
                        <div class="row mt-3" v-if="(user_id == modalObject.user_id) || (user_id == modalObject.master_id)">
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
    watch: {},
    methods: {
        //add new tasks method
        add() {
            if (this.newTask) {
                this.arrBackLog.push({name: this.newTask});
                this.newTask = "";
            }
        },
        getColumns() {
            fetch('/api/kanban_columns')
                .then(res => res.json())
                .then(res => {
                    this.columnsArr = res.data;
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
                            user_id: this.user_id
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
        this.getColumns();

    },
}
</script>

<style scoped>

</style>
