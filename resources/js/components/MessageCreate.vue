<template>
    <div>
        <textarea v-model="text" id="" class="form-control"></textarea>
        <input type="file" name="files" class="form-control-file exampleFormControlFile1 form-control my-2" multiple
               id="exampleFormControlFile1" @change="handleFileUpload($event)">
        <button class="btn btn-danger mt-2 btn-block " @click="clear()">Очистить файлы</button>
        <button class="btn btn-success mt-2 btn-block " @click="send">Отправить</button>
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
            fileForm: [],
            text: '',
        }
    },
    methods: {
        handleFileUpload(e) {
            this.fileForm.push(e.target.files[0])
        },
        async send() {
            const fd = new FormData();
            for (var i=0;i<this.fileForm.length;i++){
                fd.append('file_upload[]', this.fileForm[i])
            }
            fd.append('token',"base64:8uuu1i5d1BMpq5uBJBml8HOUPoGr7brjau5+E+V1C7I=")
            fd.append('type',this.type)
            fd.append('external_id',this.external_id)
            fd.append('user_id',this.user_id)
            fd.append('text',this.text)

            console.log(fd)
            var messageID = 0;
              await fetch(
                '/api/messages',
                {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }
            )
                .then(r => r.json()

                    // location.reload()
                ).then(r => {
                  messageID = r.id

            })
            await this.sendFile(messageID)


        },
        async sendFile(id){
            const fd = new FormData();
            for (var i=0;i<this.fileForm.length;i++){
                fd.append('file_upload[]', this.fileForm[i])
            }
            fd.append('token',"base64:8uuu1i5d1BMpq5uBJBml8HOUPoGr7brjau5+E+V1C7I=")
            await fetch(
                '/api/messages/file',
                {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                },

            ).then(r =>{
                console.log(r)
            })
        }
    }
}
</script>

<style scoped>

</style>
