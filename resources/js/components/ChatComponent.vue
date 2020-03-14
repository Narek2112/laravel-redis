<template>
    <div class="container-fluid">
        <div class="row mb-5 mt-3">
            <div class="col">
                <div class="d-flex justify-content-between">
                    <h2>Chat Name: <span class="text-primary"> {{chatName}} </span> </h2>
                    <button class="btn btn-secondary" type="button" @click="copyChatLink">Share</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="w-100 col">
                <div class="d-block" v-for="(m,index) in this.messageArray"  :key="index">
                    <!-- Sender Message-->
                    <div  v-if="m.self" class="media mb-3">
                        <div class="media-body  d-flex justify-content-start">
                            <div class="bg-primary rounded  py-2 px-3 mb-2 d-inline-block">
                            <p class="text-small mb-0 text-white">{{m.self}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Reciever Message-->
                    <div class="media  mb-3" v-if="m.other">
                        <div class="media-body d-flex justify-content-end">
                            <div class="bg-light rounded py-2 px-3 mb-2 d-inline-block">
                            <p class="text-small mb-0 text-muted ">{{ m.other }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="input-group">
                        <input id="shareLInk" type="hidden" :value="this.shareLink">
                        <input type="text" class="form-control" @keyup.enter="sendMsg" v-model="msg" placeholder="Type Message ...">
                        <div class="input-group-addon">
                            <input type="hidden" @click="sendMsg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
    export default {
        mounted() {
            let channel = this.$route.params.id
            this.channel = channel
            let _this = this
            if(typeof io === "undefined"){
                console.error('Echo server')
            }else{
                // Share ling
                this.shareLink = window.location.href
                axios.post('chat',{
                    channel
                }).then(function (response) {
                    if(response.data.status == 'success')
                    {
                        _this.chatName = response.data.chatName[0]
                        Echo.channel(channel)
                        .listen('SendMsgEvent', function (e) {
                            if (e.msg) {
                                _this.listenMsg(e.msg);
                            }
                        });
                    }
                    
                })
               
                
            }
        },
        data: function() {
          return {
            msg: '',
            messageArray: [],
            message:'',
            m :"",
            channel:"",
            chatName:"",
            shareLink : ''
          };
        },

        methods: {
          
            copyChatLink:function() {
                let $shareInput = document.querySelector('#shareLInk')
                $shareInput.setAttribute('type', 'text')    
                $shareInput.select()

                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'successful' : 'unsuccessful';
                    alert('Copied ');
                } catch (err) {
                    alert('err');
                }
                $shareInput.setAttribute('type', 'hidden')
                window.getSelection().removeAllRanges()
            },
            sendMsg: function () {
                var socketId = Echo.socketId();
                console.log('socketId=>',socketId)
                var self = this;
                if (this.msg) {
                    window.axios.defaults.headers.common['X-Socket-Id'] = socketId
                    axios.post('sendmsg',
                    {
                        message: this.msg,
                        channel:self.channel
                    }).then(function (response) {
                        if (response.data.status == 'success') {
                            self.pushMsg(self.msg);
                            self.msg = '';
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else{
                    alert('Message is empty');
                }
            },
            pushMsg: function (msg) {
                this.messageArray.push({
                    self: msg,
                });
            },
            listenMsg: function (msg) {
                this.messageArray.push({
                    other: msg,
                });
            }
        }
    }
</script>