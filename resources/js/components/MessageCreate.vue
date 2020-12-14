<template>
    <div>
        <textarea v-model="text" id="" class="form-control"></textarea>
        <button class="btn btn-success mt-2 btn-block" @click="send">Отправить</button>
    </div>
</template>

<script>
	export default {
		mounted() {
			console.log(123)
		},
		props: {
			external_id: {
				type: String,
				default() {
					return 0
				}
			},
			type: {
				type: String,
				default() {
					return ''
				}
			},
			user_id: {
				type: String,
				default() {
					return 0
				}
			},
		},
		data() {
			return {
				text: '',
			}
		},
		methods: {
			send() {
				console.log(this.type);
				fetch(
					'/api/messages',
					{
						method: 'POST',
						body: JSON.stringify(
							{
								token: 'base64:8uuu1i5d1BMpq5uBJBml8HOUPoGr7brjau5+E+V1C7I=',
								type: this.type,
								external_id: this.external_id,
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
					.then(r => {
						location.reload()
					})

			}
		}
	}
</script>

<style scoped>

</style>
