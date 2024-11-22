<template>
    <div class="card chat-box" style="margin-bottom: 0;">
        <div class="chat-search-group">
            <div class="chat-user-group mb-0 d-flex align-items-center">
                <div class="img-users call-user" v-if="user.role !== 'doctor'">
                    <a href="profile.html" v-if="this.chat">
                        <img :src="participantAvatarUrl" alt="img">
                    </a>
                    <span class="active-users bg-info"></span>
                </div>
                <div class="chat-users">
                    <div class="user-titles" v-if="this.chat">
                        <h5>{{ user.role === 'doctor' ? participantRoleLabel : participantName  }}</h5>
                    </div>
                    <div class="user-text" v-if="this.chat && user.role !== 'doctor'">
                        <p>{{ participantRoleLabel }}</p>
                    </div>
                </div>
            </div>
            <div class="chat-search-list">
                <ul>
                    <!--<li><a href="video-call.html"><img src="/assets/img/icons/chat-icon-01.svg" alt="img"></a></li>
                    <li><a href="voice-call.html"><img src="/assets/img/icons/chat-icon-02.svg" alt="img"></a></li>-->
                    <li><a href="javascript:;"><img src="/assets/img/icons/chat-icon-03.svg" alt="img"></a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ChatTalkHeader',
    props: {
        chat: {
            type: Object,
            required: false
        },
        user: {
            type: Object,
            required: true
        }
    },
    mounted() {

    },
    computed: {
        participant() {
            return this.chat && this.chat.participants ? this.chat.participants.find((participant) => participant.createdAt !== this.user.id) : null;
        },
        participantAvatarUrl() {
            let avatar = this.participant ? this.participant.user.profile.fileAvatar : null;

            return avatar && avatar.url ? avatar.url : '/assets/img/profiles/avatar-05.jpg';
        },
        participantName() {
            return this.participant ? this.participant.user.name : null;

        },
        participantRole() {
            return this.participant ? this.participant.user.role : null;
        },
        participantRoleLabel() {
            return this.participant ? this.participant.user.roleLabel : null;
        },
    },
    methods: {

    }
};
</script>
