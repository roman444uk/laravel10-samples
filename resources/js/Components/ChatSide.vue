<template>
    <div class="card chat-box-clinic ">
        <div class="chat-widgets">
            <chat-side-header></chat-side-header>

            <chat-side-search></chat-side-search>

            <chat-side-talks></chat-side-talks>
        </div>
    </div>
</template>

<script>
import ChatSideHeader from "./ChatSideHeader.vue";
import ChatSideSearch from "./ChatSideSearch.vue";
import ChatSideTalks from "./ChatSideTalks.vue";

export default {
    name: 'ChatSide',
    components: {
        ChatSideTalks, ChatSideSearch, ChatSideHeader
    },
};
</script>
