<template>
    <div class="chat-body">
        <ul class="list-unstyled chat-message">
            <template v-for="(message, messageIndex) in messages" :key="message.id">
                <chat-talk-message :message="message"
                                   :message-index="messageIndex"
                                   :prev-message="getPrevMessage(messageIndex)"
                                   :replied-message="getRepliedMessage(message)"
                                   :user="user"
                                   :chat="chat"
                                   @messageEdit="messageEdit"
                                   @messageRemove="messageRemove"
                                   @messageReply="messageReply"
                                   @scrollToReplied="scrollToRepliedMessage"
                ></chat-talk-message>
            </template>
        </ul>
    </div>
</template>

<script>
import ChatTalkMessageFiles from "./ChatTalkMessageFiles.vue";
import ChatTalkMessage from "./ChatTalkMessage.vue";

export default {
    name: 'ChatTalk',
    components: {
        ChatTalkMessage,
        ChatTalkMessageFiles
    },
    props: {
        chat: {
            type: Object,
            required: false
        },
        messages: {
            type: Array,
            required: true
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
    computed: {
        getRepliedMessage() {
            return (message) => {
                return this.messages.find((item) => item.id === message.repliedMessageId);
            }
        },
    },
    data() {
        return {
            lightBoxImages: null,
        };
    },
    mounted() {
        this.prepareChatUI();
    },
    methods: {
        getPrevMessage(index) {
            return this.messages[index - 1];
        },
        messageEdit(message) {
            this.$emit('messageEdit', message);
        },
        messageRemove(message) {
            let index = this.messages.findIndex((item) => item.id === message.id);

            this.messages.splice(index, 1);
        },
        messageReply(message) {
            this.$emit('messageReply', message);
        },
        scrollToRepliedMessage(message) {
            let messageElement = $('[data-message-id="' + message.id + '"]'),
                chatBody = document.querySelector('.chat-body');

            chatBody.scrollTop = messageElement.get(0).offsetTop;
            messageElement.addClass('selected');

            setTimeout(() => {
                messageElement.removeClass('selected');
            }, 1000);
        },
        initLightBoxImages() {
            if (this.lightBoxImages) {
                this.lightBoxImages.destroy();
            }

            setTimeout(() => {
                if (this.chat) {
                    this.lightBoxImages = GLightbox({
                        selector: '.chat-light-box-image'
                    })
                }
            }, 300);
        },
        prepareChatUI() {
            this.adjustHeight();
            this.scrollToBottom();
        },
        adjustHeight() {
            setTimeout(() => {
                if (!this.modeOffCanvas) {
                    let height = $('html body').height()
                        - $('.header').height()
                        - parseInt($('.content').css('padding-top'))
                        - $('.page-header').height()
                        - parseInt($('.page-header').css('margin-bottom'))
                        - $('.chat-box').outerHeight(true)
                        - parseInt($('.chat-box').css('margin-bottom'))
                        - parseInt($('.chat-message-box').css('padding-top'))
                        - parseInt($('.chat-message-box').css('padding-bottom'))
                        - parseInt($('.chat-message-box').css('margin-bottom'))
                        - $('.chat-footer-box').outerHeight(true);

                    $('.chat-body').css('height', height + 'px').css('max-height', height + 'px');
                } else {
                    let height = $('.offcanvas-chat .offcanvas-body').height()
                        - $('.offcanvas-chat .chat-box').outerHeight(true)
                        - parseInt($('.offcanvas-chat .chat-message-box').css('padding-top'))
                        - parseInt($('.offcanvas-chat .chat-message-box').css('padding-bottom'))
                        - parseInt($('.offcanvas-chat .chat-message-box').css('margin-bottom'))
                        - $('.offcanvas-chat .chat-footer-box').outerHeight(true);

                    $('.chat-body').css('height', height + 'px').css('max-height', height + 'px');
                }
            }, 100);
        },
        scrollToBottom() {
            setTimeout(() => {
                var chatBody = document.querySelector('.chat-body');
                if (chatBody) {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            }, 200)
        }
    }
};
</script>
