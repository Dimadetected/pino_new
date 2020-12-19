<template>
    <div class="col-12">
        <div class="text-right mb-2">
            <a href="/chains/form" class="btn btn-success">Добавить</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="chain in chains">
                <td>{{chain.id}}</td>
                <td>{{chain.name}}</td>
                <td>
                    <a :href="'/chains/form/'+chain.id" class="btn btn-primary">Редактировать</a>
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
				chains: {},
			}
		},
		watch: {},
		methods: {
			get() {
				fetch('/api/chains')
					.then(res => res.json())
					.then(res => {
						this.chains = res.data
					})
			},
		}
	}
</script>

<style scoped>

</style>
