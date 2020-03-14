<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">Create new Chat</div>
                    <div class="card-body form-group">
                        <input placeholder="enter chat name..." class="form-control mb-3" v-model="chatName"  />    
                        <button class="btn btn-success w-100" @click="createChat">Create Chat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
   
<script>
    import VueRouter from 'vue-router'

    export default {
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            createChat(){
                let self = this
                 axios.post('createChat',{
                    chatName: this.chatName,
                }).then(function (response) {
                    if(response.data.status == 'success'){
                        let link = 'chat/'+response.data.token
                        self.$router.push(link)
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        data: function() {
          return {
            chatName: ''
          };
        }
    
    }
</script>