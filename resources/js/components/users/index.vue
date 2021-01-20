<template>
    <div class="col-12">
        <table class="table">
            <thead>
            <tr>
                <th class="text-left">ФИО</th>
                <th class="text-left">Email</th>
                <th class="text-left">Роль</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users">
                <td class="text-left">{{user.name}}</td>
                <td class="text-left">{{user.email}}</td>
                <td class="text-left">{{user.user_role.name}}</td>
                <td class="text-right">
                    <a :href="'/users/form/'+user.id" class="btn btn-primary">Редактировать</a>
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
				users: {},
			}
		},
		watch: {},
		methods: {
			get() {
				fetch('/api/users')
					.then(res => res.json())
					.then(res => {
						this.users = res.data
					})
			},
		}
	}
</script>

<style scoped>

</style>
