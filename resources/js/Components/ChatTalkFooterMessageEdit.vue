<template>
    <div :class="'media d-flex align-items-start'" v-if="message && message.id">
        <div class="reply-icon flex-shrink-0">
            <i class="ion-edit"></i>
        </div>
        <div class="preview flex-shrink-0" v-if="getMessageFile()">
            <img :src="getMessageFileIconUrl()">
        </div>
        <div class="media-body flex-grow-1">
            <div class="msg-box">
                <div class="message-sub-box">
                    <h4 class="d-flex justify-content-between">
                        <strong>
                            {{ trans('chats.message_editing') }}
                        </strong>
                        <strong class="msg-btn-panel">
                            <a href="#" @click="cancel()">
                                <i class="fa fa-times"></i>
                            </a>
                        </strong>
                    </h4>
                    <p>
                        {{ getMessageFile() ? getMessageFile().name : (message.message ? message.message : '') }}
                    </p>
                    <chat-talk-message-files :files="message.files ? message.files : []"
                                             :user="user"
                    ></chat-talk-message-files>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ChatTalkMessageFiles from "./ChatTalkMessageFiles.vue";
import {FileHelper} from "../Helpers/FileHelper.js";

export default {
    name: 'ChatTalkFooterMessageEdit',
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
    mounted() {},
    computed: {
        getMessageFile() {
            return () => {
                return this.message.files && this.message.files.length ? this.message.files[0] : null;
            }
        },
        getMessageFileIconUrl() {
            return () => {
                let file = this.getMessageFile();
                if (!file) {
                    return null;
                }

                return FileHelper.getIcon(file);
                // return FileHelper.isImage(file) ? file.url : FileHelper.getIcon(file);
            }
        },
    },
    methods: {
        cancel() {
            this.$emit('messageEditCancel');
        }
    }
};
</script>
