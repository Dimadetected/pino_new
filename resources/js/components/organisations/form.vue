<template>
    <div class="col-12">
        <div class="row">
            <div class="col-12 alert alert-success" v-if="alert">
                Данные были успешно обновлены
            </div>
            <div class="col-12 card card-body shadow my-2">
                <div class="row">
                    <div class="col-md-4">
                        Название организации:
                    </div>
                    <div class="col-md-8">
                        <input type="text" v-model="organisation_name" class="form-control">
                    </div>
                </div>
            </div>
            <button class="col-12 btn btn-primary" @click="save">Сохранить</button>
        </div>
    </div>
</template>

<script>
	export default {
		mounted() {
			if (this.organisation !== 0)
				this.get();
		},
		props: {
			organisation: {
				type: Number,
				default() {
					return 0
				}
			}
		},
		data() {
			return {
				alert: false,
				organisation_name: '',
			}
		},
		watch: {},
		methods: {
			get() {
				fetch('/api/organisations/' + this.organisation)
					.then(res => res.json())
					.then(res => {
                        this.organisation_name = res.data.name
					})
			},
			save() {
				fetch(
					'/api/organisations' + (this.organisation == 0 ? '' : '/' + this.organisation),
					{
						method: this.organisation == 0 ? 'POST' : 'PUT',
						body: JSON.stringify(
							{
								token: 'base64:vfhjJc51xw1vWIRyH+JG36Xux3OP/IAAbOVQi2cjA0c=',
								name: this.organisation_name,
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
							this.data = r.data;
							console.log(r.data);
							this.alert = true;
						}
					)
			},
		}
	}
</script>

<style scoped>

</style>
