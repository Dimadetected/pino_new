<template>
    <div class="col-12">
        <div class="text-right mb-2">
            <a href="/organisations/form" class="btn btn-success">Добавить</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th class="text-center">Название</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="organisation in organisations">
                <td>{{organisation.id}}</td>
                <td class="text-center">{{organisation.name}}</td>
                <td class="text-right">
                    <a :href="'/organisations/form/'+organisation.id" class="btn btn-primary">Редактировать</a>
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
				organisations: {},
			}
		},
		watch: {},
		methods: {
			get() {
				fetch('/api/organisations')
					.then(res => res.json())
					.then(res => {
						this.organisations = res.data
					})
			},
		}
	}
</script>

<style scoped>

</style>
