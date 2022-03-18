<template>
    <div class="col-12">
        <div class="row">
            <div class="btn-group col-12 p-0 m-0" role="group" aria-label="Basic example">
                <button @click="changeToStory" class="btn btn-block  my-1 text-light"
                        :class="view === 1?'btn-primary':'btn-secondary'">История
                </button>
                <button @click="changeToChat" class="btn btn-block  my-1 text-light"
                        :class="view === 2?'btn-primary':'btn-secondary'">Чат
                </button>
            </div>
        </div>
        <div class=" card-title mt-4" style="font-size: 16pt">{{ view === 1 ? 'История взаимодействия:' : 'Чат' }}</div>
        <hr>
        <div style="overflow-y: auto;max-height: 40vh !important;">
            <div v-for="(action,index) in actions" v-if="view===1">
                <p class="my-3">{{ action.user.name }}:<br>
                    <span class="pl-4">{{ action.text }}</span>
                <div v-if="action.message"><b>Сообщение:</b>{{ action.message.text }}</div>
                <div class="text-right">{{ action.new_date }}</div>
                </p>
                <hr v-if="index !== actions.length">
            </div>
            <div v-for="(message,index) in messages" v-if="view===2">
                <p class="my-3">{{ message.user.name }}:<br>
                    <span class="pl-4">{{ message.text }}</span>
                <div class="text-right">{{ message.new_date }}</div>
                </p>
                <div v-for="img in message.images">
                    <a :href="'/'+img" download class="btn btn-primary">Скачать файл</a>
                    <a :href="'/'+img" data-fancybox data-caption="Просмотр фото" >
                        <img  :src="'/'+img" alt="" class="my-3">
                    </a>
                </div>

                <hr v-if="index !== actions.length">
            </div>
        </div>
    </div>
</template>

<script>
import '@fancyapps/fancybox/dist/jquery.fancybox.min'

export default {
    mounted() {
       console.log(new Date().getTimezoneOffset())
    },
    props: {
        actions: {
            type: Array,
            default() {
                return {}
            }
        },
        messages: {
            type: Array,
            default() {
                return {}
            }
        }
    },
    data() {
        return {
            view: 1
        }
    },
    methods: {
        parseDate(date) {
            console.log(new Date(date))
        },
        changeToStory() {
            this.view = 1;
        },
        changeToChat() {
            this.view = 2;
        }
    }
}
</script>

<style scoped>

</style>
