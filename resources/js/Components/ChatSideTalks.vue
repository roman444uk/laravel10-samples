<template>
    <template v-for="(dataChat, index) in dataChatsSorted" :key="dataChat.id">
        <div :class="'chat-user-group d-flex align-items-center' + (chat && chat.id === dataChat.id ? ' selected' : '')"
             @click="chatOpen(dataChat)"
        >
            <div class="img-users call-user" v-if="user.role !== 'doctor'">
                <a href="profile.html" v-if="participant(dataChat)">
                    <img :src="participantAvatarUrl(dataChat)" alt="img">
                </a>
                <span class="active-users bg-info" v-if="participant(dataChat)"></span>
            </div>
            <div class="chat-users">
                <div class="user-titles d-flex">
                    <h5>{{ chatTitle(dataChat) }}</h5>
                    <div class="chat-user-time">
                        <p>{{ chatLastMessageTime(dataChat) }}</p>
                    </div>
                </div>
                <div class="user-text d-flex">
                    <p style="opacity: 0;">{{ chatSubTitle(dataChat) }}</p>
                    <div class="chat-user-count" v-if="dataChat.not_viewed_messages_count > 0">
                        <span>{{ dataChat.not_viewed_messages_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>

<script>
import axios from "axios";

export default {
    name: 'ChatSideTalks',
    props: {
        chat: {
            type: Object,
            required: false
        },
        chats: {
            type: Array,
            required: true
        },
        user: {
            type: Object,
            required: true
        }
    },
    emits :{
        chatOpen: null,
    },
    data() {
        return {
            dataChats: [],
        };
    },
    mounted() {
        let chats = [];

        (this.chats ? this.chats : []).forEach((chat) => {
            chats.push(chat);
        });

        this.dataChats = chats;
    },
    watch: {
        chats: {
            deep: true,
            immediate: true,
            handler(chats) {
                this.dataChats = chats;
            },
        },
    },
    computed: {
        dataChatsSorted() {
            return this.dataChats.sort((chatOne, chatTwo) => {
                let chatOneTimestamp = chatOne.lastMessage ? chatOne.lastMessage.createdAt.timestamp : chatOne.createdAt.timestamp,
                    chatTwoTimestamp = chatTwo.lastMessage ? chatTwo.lastMessage.createdAt.timestamp : chatTwo.createdAt.timestamp;

                if (chatOneTimestamp === chatTwoTimestamp) {
                    return 0;
                }

                return chatOneTimestamp < chatTwoTimestamp ? 1 : -1;
            });
        },
        chatTitle() {
            return (chat) => {
                return chat.title
            }
        },
        chatSubTitle() {
            return (chat) => {
                return chat.title
            }
        },
        participant() {
            return (chat) => {
                return chat.participants ? chat.participants.find((participant) => participant.createdAt !== this.user.id) : null;
            }
        },
        participantAvatarUrl() {
            return (chat) => {
                let avatarUrl = this.participant(chat).user.avatarUrl;

                return avatarUrl ? avatarUrl : '/assets/img/profiles/avatar-05.jpg'
            }
        },
        /*chatDescription() {
            return (chat) => {
                return trans('orders.order_number', {number: chat.context_id}) + (
                    chat.title ? '(' + chat.title + ')'
                );
            }
        },*/
        participantName() {
            return (chat) => {
                return this.participant(chat).user.name;
            }
        },
        participantRole() {
            return (chat) => {
                return this.participant(chat).user.role;
            }
        },
        participantRoleLabel() {
            return (chat) => {
                return this.participant(chat).user.roleLabel;
            }
        },
        chatLastMessageTime(chat) {
            return (chat) => {
                return chat.lastMessage
                    ? chat.lastMessage.createdAt.time + ' ' + chat.lastMessage.createdAt.date
                    : chat.createdAt.time + ' ' + chat.createdAt.date;
            }
        }
    },
    methods: {
        chatOpen: function (chat) {
            this.$emit('chatOpen', chat);
        },
        markChatAsViewed(chat) {
            this.dataChats.forEach((dataChat) => {
                if (dataChat.id === chat.id) {
                    dataChat.notViewedMessagesCount = 0;
                }
            });
        },
        newMessage: function (message) {
            this.dataChats.forEach((dataChat) => {
                if (dataChat.id === message.chatId) {
                    dataChat.lastMessage = message;

                    if (message.createdAt !== this.user.id) {
                        dataChat.not_viewed_messages_count += 1;
                    }
                }
            });
        },
    }
};
</script>
<script setup>
</script>
