<template>
    <div class="col-12">
        <div class=" card-title mt-4" style="font-size: 16pt">История</div>
        <hr>
        <div style="overflow-y: auto;max-height: 40vh !important;">
            <div v-for="(action,index) in actions">
                <p class="my-3"><span v-if="action.user">{{ action.user.name }}</span>:<br>
                    <span class="pl-4 mt-2">{{ action.text }}</span>
                <div v-if="action.message"><b>Сообщение:</b>{{ action.message.text }}</div>
                <div class="text-right">{{ action.new_date }}</div>
                </p>
                <div v-if="action.images">
                    <div v-for="img in action.images">
                        {{img}}
                        <a :href="'/'+img" download class="btn btn-primary">Скачать файл</a>
                        <a :href="'/'+img" data-fancybox data-caption="Просмотр фото">
                            <img :src="'/'+img" alt="" class="my-3">
                        </a>
                    </div>
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
        this.filter()
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
        filter() {
            var newArray = []
            var i
            var j
            for (i = 0; i < this.actions.length; i++) {
                for (j = 0; j < this.actions[i].length; j++) {
                    newArray.push(this.actions[i][j])
                }
            }
            newArray.sort((a, b) => a.updated_at < b.updated_at ? 1 : -1);
            console.log(newArray)
            this.actions = newArray
        },
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
