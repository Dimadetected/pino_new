<template>
    <div class="col-12 mt-5">
        <div class="row mt-5">
            <div class="col-1"></div>
            <div class="col-md-2" v-for="(column,index) in columnsArr">
                <div class="p-2 alert " :class="alertsArr[index]">
                    <h3>{{column.text}} {{alertsArr.shift}}</h3>
                    <!-- Backlog draggable component. Pass arrBackLog to list prop -->
                    <draggable
                        class="list-group kanban-column"
                        :list="column.tasks"
                        group="tasks"
                        @change="log"
                    >
                        <div
                            class="list-group-item"
                            v-for="task in column.tasks"
                            :key="task.name"
                        >
                            <div class="pb-2 border-bottom">{{ task.name }}</div>
                            <div style="font-size: 10px">
                                <table class="table table-borderless mt-1">
                                    <tbody>
                                    <tr>
                                        <td class="p-0">Дал:</td>
                                        <td class="p-0">{{task.user}}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">Делает:</td>
                                        <td class="p-0">{{task.master}}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">Осталось:</td>
                                        <td class="p-0">{{task.date}}ч.</td>
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
</template>

<script>
import draggable from 'vuedraggable';

export default {
    components: {
        draggable
    },
    data() {
        return {
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
        }
    },
    mounted() {
        this.getColumns();

    },
}
</script>

<style scoped>

</style>
