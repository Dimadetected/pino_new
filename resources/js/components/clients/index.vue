<template>
    <div class="col-12">
        <div class="row">
            <div class="col-2">ИНН:</div>
            <input type="text" class="form-control col-9 offset-1" v-model="inn" @keyup="get" placeholder="ИНН">
        </div>
        <table class="table">
            <thead>
            <tr>
                <th class="text-left">Наименование</th>
                <th class="text-left">Инн</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="client in clients">
                <td class="text-left"><a :href="'/clients/'+ client.id">{{ client.name }}</a></td>
                <td class="text-left">{{ client.inn }}</td>
                <td class="text-right">
                    <a :href="'/clients/form/'+client.id" class="btn btn-primary">Редактировать</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    mounted() {
        this.get()
    },
    data() {
        return {
            inn: "",
            clients: {},
        }
    },
    watch: {},
    methods: {
        get() {
            fetch('/api/clients?inn='+this.inn)
                .then(res => res.json())
                .then(res => {
                    this.clients = res.data
                })
        },
    }
}
</script>

<style scoped>

</style>
