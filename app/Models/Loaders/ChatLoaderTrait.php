<?php

namespace App\Models\Loaders;

trait ChatLoaderTrait
{

    public function loadMessages(): self
    {
        $this->load(['messages.files', 'messages.repliedMessage']);

        return $this;
    }

    public function loadParticipants(): self
    {
        $this->load('participants.user.profile.fileAvatar');

        return $this;
    }
}
