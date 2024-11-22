<template>
    <li class="media-date" v-if="isPrevMessageDateSame()">
        <h4 class="fs-7 text-center text-muted">
            {{ message.createdAt.date }}
        </h4>
    </li>
    <li :class="'media d-flex' + getMediaCssClass()"
        :data-message-id="message.id"
    >
        <div class="avatar flex-shrink-0" v-if="showAvatar(message)">
            <img v-if="!isPrevMessageSenderSame()"
                 class="avatar-img rounded-circle"
                 :src="getMessageSenderAvatarUrl()" alt="User Image"
            >
        </div>
        <div class="media-body flex-grow-1">
            <div class="msg-box">
                <div class="message-sub-box">
                    <h4 class="d-flex justify-content-between">
                        <strong>
                            {{ showSenderName() && getMessageSender(message) ? getMessageSender(message).user.name : '' }}
                        </strong>
                        <strong class="msg-btn-panel">
<!--                            <a href="#" @click.prevent="messageRemove(message)" v-if="!isMessageIncome()">-->
<!--                                <i class="fa fa-trash-alt"></i>-->
<!--                            </a>-->
<!--                            <a href="#" @click.prevent="messageEdit(message)" v-if="!isMessageIncome()">-->
<!--                                <i class="ion-edit"></i>-->
<!--                            </a>-->
                            <a href="#" @click.prevent="messageReply(message)">
                                <i class="ion-reply"></i>
                            </a>
                        </strong>
                    </h4>
                    <div :class="'replied-message' + getMediaReplyCssClass(repliedMessage)"
                         v-if="repliedMessage"
                         @click="scrollToReplied()"
                    >
                        <h4 class="d-flex justify-content-between" v-if="getMessageSender(repliedMessage)">
                            <strong>
                                {{ getMessageSender(repliedMessage).user.name }}
                            </strong>
                        </h4>
                        <p>
                            <img :src="getMessageFileIconUrl(repliedMessage)" v-if="getMessageFile(repliedMessage)">
                        </p>
                        <p v-html="getMessageFile(repliedMessage) ? '' : repliedMessage.message"></p>
                    </div>
                    <chat-talk-message-files :files="message.files ? message.files : []"
                                             :user="user"
                                             @remove="fileRemove"
                    ></chat-talk-message-files>
                    <p v-html="message.message ? message.message : ''"></p>
                    <span>{{ message.createdAt.time }} <!--06:00 PM, 30 Sep 2022--></span>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
import ChatTalkMessageFiles from "./ChatTalkMessageFiles.vue";
import axios from "axios";
import {FileHelper} from "../Helpers/FileHelper.js";

export default {
    name: 'ChatTalkMessage',
    components: {
        ChatTalkMessageFiles
    },
    props: {
        chat: {
            type: Object,
            required: true
        },
        message: {
            type: Object,
            required: true
        },
        messageIndex: {
            type: Number,
            required: true
        },
        prevMessage: {
            type: Object,
            required: true
        },
        repliedMessage: {
            type: Object,
            required: false
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            lightBoxImages: null,
        };
    },
    mounted() {
    },
    computed: {
        getMessageSenderAvatarUrl() {
            return () => {
                let participant = this.chat.participants.find((participant) => participant.userId === this.message.userId),
                    avatarUrl = participant.user.avatarUrl;

                return avatarUrl ? avatarUrl : '/assets/img/profiles/avatar-05.jpg'
            }
        },
        getMessageSender() {
            return (message) => {
                message = message ? message : this.message;

                return this.chat.participants.find((participant) => participant.userId === message.userId);
            }
        },
        getMessageFile() {
            return (message) => {
                return message && message.files && message.files.length ? message.files[0] : null;
            }
        },
        getMessageFileIconUrl() {
            return (message) => {
                let file = this.getMessageFile(message);
                if (!file) {
                    return null;
                }

                return FileHelper.isImage(file) ? file.url : FileHelper.getIcon(file);
            }
        },
        isMessageFileImage() {
            return (message) => {
                let file = this.getMessageFile(message);
                if (!file) {
                    return false;
                }

                return FileHelper.isImage(file);
            }
        }
    },
    methods: {
        getMediaCssClass() {
            return [
                this.isMessageIncome() ? ' received' : ' sent',
                this.isPrevMessageSenderSame() ? ' same-sender' : '',
                this.message.type,
                this.message.subType
            ].join(' ');
        },
        getMediaReplyCssClass(repliedMessage) {
            return [
                this.isMessageFileImage(repliedMessage) ? ' replied-image' : '',
                repliedMessage.type,
                repliedMessage.subType
            ].join(' ');
        },
        showAvatar(message) {
            return this.isMessageIncome() && this.getMessageSender(message) && !this.isMessageSystem();
        },
        showSenderName() {
            return this.isMessageIncome() && !this.isPrevMessageSenderSame() && !this.isMessageSystem();
        },
        isMessageSystem() {
            return this.message.type === 'system';
        },
        isMessageIncome() {
            return this.user.id !== this.message.userId || this.isMessageSystem();
        },
        isPrevMessageDateSame() {
            return !this.prevMessage || (this.prevMessage.createdAt.date !== this.message.createdAt.date);
        },
        isPrevMessageSenderSame() {
            return this.prevMessage && this.prevMessage.userId === this.message.userId;
        },
        messageReply() {
            this.$emit('messageReply', this.message);
        },
        messageEdit() {
            this.$emit('messageEdit', this.message);
        },
        messageRemove() {
            let app = this;

            axios.delete(route('chat-message.destroy', {chat_message: this.message.id}))
                .then(function (response) {
                    app.$emit('messageRemove', app.message);
                })
                .catch(function (error) {
                    showErrorPopupFromResponseData(error.response.data);
                });
        },
        scrollToReplied() {
            this.$emit('scrollToReplied', this.repliedMessage);
        },
        fileRemove(file) {
            let app = this;

            axios.delete(route('chat-message.destroy-file', {file: file.id}))
                .then(function (response) {
                    app.$emit('fileRemove', file);
                })
                .catch(function (error) {
                    showErrorPopupFromResponseData(error.response.data);
                });
        },
    }
};
</script>
