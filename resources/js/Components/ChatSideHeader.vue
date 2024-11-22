<template>
    <div class="chat-user-group-head d-flex align-items-center">
        <div class="img-users call-user">
            <a href="profile.html"><img src="/assets/img/profiles/avatar-01.jpg" alt="img"></a>
            <span class="active-users"></span>
        </div>
        <div class="chat-users user-main">
            <div class="user-titles">
                <h5> William Stephin </h5>
                <div class="chat-user-time">
                    <p>Doctor</p>
                </div>
            </div>
            <div class="drop-item chat-menu user-dot-list">
                <a href="#" data-bs-toggle="dropdown" aria-expanded="false" class="">
                    <i class="feather-more-vertical"></i>
                </a>
                <div class="dropdown-menu" style="">
                    <a class="dropdown-item" href="javascript:;"><i class="feather-user me-2 text-primary"></i> Profile</a>
                    <a class="dropdown-item" href="javascript:;"><i class="feather-plus-circle me-2 text-success"></i> Archive</a>
                    <a class="dropdown-item" href="javascript:;"><i class="feather-trash-2 me-2 text-danger"></i> Delete</a>
                    <a class="dropdown-item " href="javascript:;"><i class="feather-slash me-2 text-secondary"></i> Block</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ChatSideHeader',
    props: {
        user: {
            type: Object,
            required: true
        }
    },
};
</script>
