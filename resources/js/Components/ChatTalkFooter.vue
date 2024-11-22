<template>
    <div class="chat-footer-box">
        <div class="discussion-sent">
            <div class="row gx-2">
                <div class="col-lg-12">
                    <div class="footer-discussion">
                        <chat-talk-footer-message-reply :message="repliedMessage"
                                                        :user="user"
                                                        :chat="chat"
                                                        @messageReplyCancel="messageReplyCancel"
                        ></chat-talk-footer-message-reply>

                        <chat-talk-footer-message-edit :message="editableMessage"
                                                       :user="user"
                                                       :chat="chat"
                                                       @messageEditCancel="messageEditCancel"
                        ></chat-talk-footer-message-edit>

                        <chat-talk-message-files :files="messageFiles"
                                                 :user="user"
                                                 @remove="removeFile"
                        ></chat-talk-message-files>

                        <div class="custom-file-container multiple"
                             :data-upload-id="fileUploaderId"
                             style="position: fixed; left: 100%;"
                        >
                            <label>
                                Upload (Allow Multiple)
                                <a href="javascript:void(0)" class="custom-file-container__image-clear"
                                   title="Clear Image">x</a>
                            </label>
                            <label class="custom-file-container__custom-file">
                                <input class="custom-file-container__custom-file__custom-file-input"
                                       type="file"
                                       multiple
                                >
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>

                        <div class="inputgroups">
                            <input type="text"
                                   v-model="messageText"
                                   :placeholder="messageInputPlaceholder()"
                                   @keydown="onMessageTextKeyDown($event)"
                                   @keyup="onMessageTextKeyUp($event)"
                                   :disabled="!chat"
                            >
                            <!--<div class="micro-text position-icon">
                                <img src="/assets/img/icons/chat-foot-icon-04.svg" alt="">
                            </div>-->
                            <div
                                :class="'send-chat position-icon comman-flex' + (chat && chat.id ? ' active' : ' disabled' )"
                                @click.prevent="sendMessage"
                            >
                                <a href="#">
                                    <img src="/assets/img/icons/chat-foot-icon-03.svg" alt="">
                                </a>
                            </div>
                            <div class="symple-text position-icon">
                                <ul>
                                    <!--<li><a href="javascript:;"><img src="/assets/img/icons/chat-foot-icon-01.svg" class="me-2" alt=""></a></li>-->
                                    <li class="add-smile">
                                        <a>
                                            <img src="/assets/img/icons/chat-foot-icon-02.svg" alt="">
                                        </a>
                                    </li>
                                    <li class="add-file" @click="addFile">
                                        <a>
                                            <img src="/assets/img/icons/clip.svg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ChatTalkMessageFiles from "./ChatTalkMessageFiles.vue";
import {FileHelper} from "../Helpers/FileHelper.js";

import axios from "axios";
import ChatTalkFooterMessageEdit from "./ChatTalkFooterMessageEdit.vue";
import ChatTalkFooterMessageReply from "./ChatTalkFooterMessageReply.vue";

export default {
    name: 'ChatTalkFooter',
    components: {
        ChatTalkFooterMessageEdit,
        ChatTalkFooterMessageReply,
        ChatTalkMessageFiles
    },
    props: {
        chat: {
            type: Object,
            required: false
        },
        editableMessage: {
            type: Object,
            required: false
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
            fileUploaderId: 'chat-file-uploader',
            messageId: null,
            messageFiles: [],
            messageRepliedId: null,
            messageText: null,
            messages: null,
        };
    },
    watch: {
        editableMessage: {
            handler(message) {
                this.messageId = message ? message.id : null;
                this.messageRepliedId = message && message.repliedMessage ? message.repliedMessage.id : null;
                this.messageText = message ? message.message : null;

                this.messageFiles = [];

                if (message && message.files) {
                    this.messageFiles = [];
                }
            },
            deep: true,
            immediate: true
        },
    },
    mounted() {
        let app = this;

        initFileUploadWithPreview(this.fileUploaderId, {
            instantUploading: false,
            onImagesAdded: (fileUpload, cachedFile) => {
                app.messageFiles.push({
                    id: cachedFile.token,
                    name: cachedFile.name,
                    size: cachedFile.size,
                    file: null,
                    cachedFile: cachedFile,
                    icon: FileHelper.getIconByName(cachedFile.name)
                })
            },
            onImageDeleted: (fileUpload, file) => {

            },
        });
    },
    methods: {
        onMessageTextKeyDown(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        },
        onMessageTextKeyUp(event) {
            if (event.keyCode === 13) {
                this.sendMessage();
            }
        },
        addFile(event) {
            $(document.querySelector('[data-upload-id="' + this.fileUploaderId + '"] .custom-file-container__custom-file')).click();
        },
        removeFile(file) {
            let index = this.messageFiles.findIndex((item) => item.id === file.id);

            this.messageFiles.splice(index, 1);
        },
        messageInputPlaceholder() {
            return trans('chats.type_your_message');
        },
        messageEditCancel() {
            this.$emit('messageEditCancel');
        },
        messageReplyCancel() {
            this.$emit('messageReplyCancel');
        },
        sendMessage() {
            let app = this;

            if (!this.messageText && this.messageFiles.length === 0) {
                return;
            }

            let formData = new FormData();
            formData.append('chat_id', this.chat.id);
            formData.append('message', this.messageText ? this.messageText : '');

            if (this.repliedMessage && this.repliedMessage.id) {
                formData.append('replied_message_id', this.repliedMessage.id);
            }
            if (this.messageRepliedId) {
                formData.append('replied_message_id', this.messageRepliedId);
            }

            this.messageFiles.forEach((messageFile) => {
                formData.append('file[]', messageFile.cachedFile);
            });

            axios.post(this.messageId ? route('chat-message.update', {chat_message: this.messageId}) : route('chat-message.store'), formData)
                .then(function (response) {
                    let message = response.data.data.message;

                    app.messageId = null;
                    app.messageText = null;
                    app.messageFiles = [];

                    if (app.editableMessage) {
                        app.messageEditCancel();
                    }

                    app.$emit('messageSent', message);
                })
                .catch(function (error) {
                    showErrorPopupFromResponseData(error.response.data);
                });
        }
    }
};
</script>

