<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Managers can view all resident documents; residents can only view their own.
     */
    public function view(User $user, Document $document): bool
    {
        return $user->role === 'manager' || $document->user_id === $user->id;
    }
}
