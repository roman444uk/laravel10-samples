<template>
    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 d-flex" v-if="!order">
            <div class="card chat-box-clinic">
                <div class="chat-widgets">
                    <chat-side-header :user="user"
                                      :chat="dataChat"
                    ></chat-side-header>

                    <chat-side-search></chat-side-search>

                    <chat-side-talks :ref="`chat-side-talks`"
                                     :chat="dataChat"
                                     :chats="dataChats"
                                     :user="user"
                                     @chatOpen="chatOpen"
                    ></chat-side-talks>
                </div>
            </div>
        </div>
        <div :class="order ? 'col-sm-12 col-md-12 col-lg-12 col-xl-12' : 'col-sm-12 col-md-8 col-lg-8 col-xl-8'">
            <chat-talk-header :chat="dataChat"
                              :user="user"
            ></chat-talk-header>

            <div class="card chat-message-box" v-show="dataChat" style="padding-top: 0;">
                <div class="card-body p-0">
                    <chat-talk :ref="`chat-talk-messages`"
                               :chat="dataChat"
                               :messages="dataMessages"
                               :user="user"
                               :mode-off-canvas="modeOffCanvas"
                               @messageEdit="messageEdit"
                               @messageReply="messageReply"
                               @removeFile="removeMessageFile"
                    ></chat-talk>

                    <chat-talk-footer :chat="dataChat"
                                      :user="user"
                                      :replied-message="dataRepliedMessage"
                                      :editable-message="dataEditableMessage"
                                      @messageSent="messageSent"
                                      @messageReplyCancel="messageReplyCancel"
                                      @messageEditCancel="messageEditCancel"
                    ></chat-talk-footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ChatSideHeader from "./ChatSideHeader.vue";
import ChatSideTalks from "./ChatSideTalks.vue";
import ChatSideSearch from "./ChatSideSearch.vue";
import ChatTalkHeader from "./ChatTalkHeader.vue";
import ChatTalk from "./ChatTalk.vue";
import ChatTalkFooter from "./ChatTalkFooter.vue";
import axios from "axios";

export default {
    name: 'Chat',
    components: {
        ChatTalkFooter, ChatTalk, ChatTalkHeader, ChatSideSearch, ChatSideTalks, ChatSideHeader
    },
    props: {
        order: {
            type: Object,
            required: false
        },
        chat: {
            type: Object,
            required: false
        },
        chats: {
            type: Array,
            required: false
        },
        modeOffCanvas: {
            type: Boolean,
            required: false
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            dataChat: {},
            dataChats: [],
            dataMessages: [],
            dataRepliedMessage: {},
            dataEditableMessage: {},
        };
    },
    beforeMount() {
    },
    mounted() {
        let chats = [];
        let messages = [];

        (this.chats ? this.chats : []).forEach((chat) => {
            chats.push(chat);
        });

        (this.chat && this.chat.messages ? this.chat.messages : []).forEach((message) => {
            messages.push(message);
        });

        this.dataChat = this.chat;
        this.dataChats = chats;
        this.dataMessages = messages;

        this.listenEvents();

        this.$refs[`chat-talk-messages`].initLightBoxImages();
    },
    methods: {
        chatCreated(chat) {
            if (!this.dataChats.find((dataChat) => dataChat.id === chat.id)) {
                this.dataChats.push(chat);
            }
        },
        chatOpen(chat) {
            let app = this;

            axios.get(route('chat.show', {chat: chat.id}))
                .then(function (response) {
                    let data = response.data.data;

                    app.dataChat = data.chat;
                    app.dataMessages = data.chat.messages;

                    app.$refs[`chat-talk-messages`].initLightBoxImages();
                    app.$refs[`chat-talk-messages`].prepareChatUI();

                    app.markChatAsViewed(data.chat);
                })
                .catch(function (error) {
                    if (error.response) {
                        showErrorPopupFromResponseData(error.response.data);
                    }
                });
        },
        messageEdit(message) {
            this.messageReplyCancel();

            this.dataEditableMessage = message;
        },
        messageEditCancel(message) {
            this.dataEditableMessage = null;
        },
        messageReply(message) {
            this.messageEditCancel();

            this.dataRepliedMessage = message;
        },
        messageReplyCancel() {
            this.dataRepliedMessage = null;
        },
        messageSent(message) {
            if (this.dataChat && this.dataChat.id === message.chatId) {
                let index = this.dataMessages.findIndex((dataMessage) => dataMessage.id === message.id)
                if (index >= 0) {
                    this.dataMessages[index] = message;
                } else {
                    if (this.dataRepliedMessage && this.dataRepliedMessage.id) {
                        message.repliedMessage = this.dataRepliedMessage;
                    }
                    this.dataMessages.push(message);
                }
            }

            this.dataRepliedMessage = null;

            let chatSideTalksVue = this.$refs[`chat-side-talks`];
            if (chatSideTalksVue) {
                chatSideTalksVue.newMessage(message);
            }

            if (message.files.find((file) => this.whetherFileImage(file))) {
                this.$refs[`chat-talk-messages`].initLightBoxImages();
            }

            this.$refs[`chat-talk-messages`].scrollToBottom();

            this.markChatAsViewed(this.dataChat);
        },
        messageChanged(message) {
            if (this.dataChat && this.dataChat.id === message.chatId) {
                let messageIndex = this.dataMessages.findIndex((dataMessage) => dataMessage.id === message.id);
                this.dataMessages[messageIndex] = message;
            }
        },
        messageRemoved(message) {
            if (this.dataChat && this.dataChat.id === message.chatId) {
                let messageIndex = this.dataMessages.findIndex((dataMessage) => dataMessage.id === message.id);
                this.dataMessages.splice(messageIndex, 1);
            }
        },
        whetherFileImage(file) {
            return ['gif', 'jpg', 'jpeg', 'png'].indexOf(file.ext) >= 0
        },
        markChatAsViewed(chat) {
            let app = this;

            axios.post(route('chat.mark-as-viewed', {chat: chat.id}))
                .then(function (response) {
                    app.$refs[`chat-side-talks`].markChatAsViewed(chat);
                })
                .catch(function (error) {
                    if (error.response) {
                        showErrorPopupFromResponseData(error.response.data);
                    }
                });
        },
        removeMessageFile(file) {
            if (this.whetherFileImage(file)) {
                this.$refs[`chat-talk-messages`].initLightBoxImages();
            }
            // let message = this.dataMessages.find((message) => message.id === file.ownerId);
            // if (message && message.files) {
            //     let fileIndex = message.files.findIndex((item) => item.id === file.id);
            //     message.files.splice(fileIndex, 1);
            // }
        },
        listenEvents() {
            let app = this;

            window.Echo.private(`chats.participants.` + this.user.id)
                .listen('.chat.created', (e) => {
                    console.log('chat.created');
                    app.chatCreated(e.chat);
                })
                .listen('.message.created', (e) => {
                    console.log('message.created');
                    app.messageSent(e.message);
                })
                .listen('.message.updated', (e) => {
                    console.log('message.changed');
                    app.messageChanged(e.message);
                })
                .listen('.message.destroyed', (e) => {
                    console.log('message.destroyed');
                    app.messageRemoved(e.message);
                });
        },
    }
}
;
</script>
