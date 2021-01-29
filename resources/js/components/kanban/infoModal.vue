<template>
    <div>
        <div class="row">
            <div class="col-12">

                <div class="btn-group " style="width: 100%" role="group" aria-label="Basic example">
                    <button type="button" @click="selectedBtn=1" style="font-size: 20px" class="btn"
                            :class="selectedBtn===1?'btn-primary':'btn-secondary'">Комментарии
                    </button>
                    <button type="button" @click="selectedBtn=2" style="font-size: 20px" class="btn"
                            :class="selectedBtn===2?'btn-primary':'btn-secondary'">История
                    </button>
                </div>
            </div>
        </div>
        <div v-if="selectedBtn===1">
            <div style="min-height: 40vh;max-height: 40vh;overflow-y: scroll">

                <div v-for="comment in commentsArr" class="card card-body my-2">
                    <div class="row">
                        <div class="col-6">{{ comment.user.name }}</div>
                        <div class="col-6 text-right">{{ comment.date }}</div>
                    </div>
                    {{ comment.text }}
                </div>
            </div>
            <div class="mt-5 text-right">
                <textarea v-model="text" id="" class="form-control"></textarea>
                <button class="btn btn-success mt-1" @click="sendMessage()">Отправить</button>
            </div>
        </div>
        <div v-else>
            <div style="max-height: 40vh;overflow-y: scroll">

                <div v-for="log in logsArr" class="card card-body my-2">
                    <div class="row">
                        <div class="col-6">{{ log.user.name }}</div>
                        <div class="col-6 text-right">{{ log.date }}</div>
                    </div>
                    <span v-html="log.text"></span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    components: {},
    props: {
        id: Number,
        user_id: Number,
    },
    data() {
        return {
            selectedBtn: 1,
            text: '',
            commentsArr: [],
            logsArr: [],
        };
    },
    watch: {},
    methods: {
        getInfo() {
            fetch('/api/kanban_tasks/' + this.id)
                .then(res => res.json())
                .then(res => {
                    console.log(res.data);
                    this.commentsArr = res.data.comments;
                    this.logsArr = res.data.logs;
                })
        },
        sendMessage() {
            fetch(
                '/api/kanban_tasks/message',
                {
                    method: 'POST',
                    body: JSON.stringify(
                        {
                            id: this.id,
                            user_id: this.user_id,
                            text: this.text,
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
                        this.getInfo();
                    }
                )
        }
    },
    mounted() {
        this.getInfo();
    },
}
</script>

<style scoped>

</style>
