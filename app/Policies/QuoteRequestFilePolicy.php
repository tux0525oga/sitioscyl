<?php

namespace App\Policies;

use App\Models\QuoteRequestFile;
use App\Models\UserAccount;

class QuoteRequestFilePolicy
{
    public function view(
        UserAccount $user,
        QuoteRequestFile $quoteRequestFile
    ): bool {
        return $user->hasAdminAccess();
    }
}