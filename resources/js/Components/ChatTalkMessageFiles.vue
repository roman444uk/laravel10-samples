<template>
    <ul class="msg-sub-list" v-if="files.length">
        <li v-for="file in files" :key="file.id || file.name">
            <template v-if="FileHelper.isImage(file)">
                <a :href="file.url"
                   class="chat-light-box-image"
                   :data-title="file.name"
                   :data-description="file.name"
                >
                    <img class="rounded"
                         :src="getImagePreviewUrl(file)"
                         style="width: 237px;"
                    >
                </a>
            </template>
            <template v-else>
                <img :src="file.icon" alt="" class="me-1">
                <a href="#" @click.prevent="download(file)">
                    {{ file.name }}
                </a>
                <span class="ms-1">
                    {{ FileHelper.getSizeHuman(file.size) }}
                </span>
                <img v-if="false"
                     class="ml-1 cursor-pointer"
                     src="/assets/img/icons/chat-icon-07.svg"
                     alt=""
                     style="margin-left: 5px;"
                     @click="download(file)"
                >
                <a v-if="!file.url" href="#" class="ms-1 fs-5 cursor-pointer " @click="remove(file)">
                    <i class="fa fa-times"></i>
                </a>
            </template>
<!--            <img v-if="!user || file.userId === user.id"-->
<!--                 class="cursor-pointer"-->
<!--                 src="/assets/img/icons/trash.svg"-->
<!--                 alt=""-->
<!--                 style="margin-left: 4px; margin-right: 4px;"-->
<!--                 @click="remove(file)"-->
<!--            >-->
<!--                <i class="feather-x  ms-2 text-danger cursor-pointer"-->
<!--                   @click="remove(file)"-->
<!--                ></i>-->
        </li>
    </ul>
    <!--    <ul class="msg-sub-list">-->
    <!--        <li>-->
    <!--            <img src="/assets/img/icons/chat-icon-04.svg" alt="" class="me-1">-->
    <!--            Explainer Video.avi<span class="ms-1">30.0 MB</span>-->
    <!--            <img src="/assets/img/icons/chat-icon-07.svg" alt="" class="ms-1 ms-auto">-->
    <!--        </li>-->
    <!--        <li>-->
    <!--            <img src="/assets/img/icons/chat-icon-05.svg" alt="" class="me-1">-->
    <!--            Ayush Therapy.mp3<span class="ms-1">4.0 MB</span>-->
    <!--            <img src="/assets/img/icons/chat-icon-08.svg" alt="" class="ms-1 ms-auto">-->
    <!--        </li>-->
    <!--        <li>-->
    <!--            <img src="/assets/img/icons/chat-icon-06.svg" alt="" class="me-1">-->
    <!--            The liver.img<span class="ms-1">520KB</span>-->
    <!--        </li>-->
    <!--    </ul>-->
</template>

<script>
import {FileHelper} from "../Helpers/FileHelper.js";

export default {
    name: 'ChatTalkMessageFiles',
    props: {
        files: {
            type: Array,
            required: false
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {};
    },
    mounted() {
    },
    computed: {
        FileHelper() {
            return FileHelper
        }
    },
    methods: {
        download: function (file) {
            window.open(file.url, '_blank');
        },
        remove: function (file) {
            this.$emit('remove', file);
        },
        getImagePreviewUrl(file) {
            return file.previews && file.previews['237x']
                ? file.previews['237x'].url
                : route('image-preview.show-s3', {file: file.id, width: 237});
        }
    }
};
</script>
