<template>
    <div class="row">
        <div class="col-12">
            <div class="btn-group col-12 p-0 m-0" role="group" aria-label="Basic example" v-if="bill.chain.value.length !== (bill.steps-1)">
                <button @click="accept" class="btn btn-block btn-success my-1 text-light">Утвердить</button>
                <button @click="decline" class="btn btn-block btn-danger my-1 text-light">Отказать</button>
            </div>
        </div>
        <div class="col-12">
            <div class="col-12 my-2" v-if="showOrHideFlag">
                <textarea v-model="textArea" id="" class="form-control"></textarea>
            </div>
            <div class="col-12">
                <button v-if="showOrHideFlag" class="btn btn-primary btn-block" @click="send">Применить</button>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		name: 'BillStatusChange',
		mounted() {
			console.log(123)
		},
		props: {
			bill: {
				type: Object,
				default() {
					return {}
				}
			}
		},
		data() {
			return {
				type: 'decline',
				textArea: '',
				showOrHideFlag: false
			}
		},
		methods: {
			accept() {
				this.type = 'accept';
				this.showOrHideFlag = true;
			},
			decline() {
				this.type = 'decline';
				this.showOrHideFlag = true;
			},
			send() {
				fetch('/bill/consult?' + new URLSearchParams({
					bill: this.bill.id,
					type: this.type,
					text: this.textArea,
				}))
					.then(res => {
						// location.reload()

						console.log(res);
					})
			}
		}
	}
</script>

<style scoped>

</style>
