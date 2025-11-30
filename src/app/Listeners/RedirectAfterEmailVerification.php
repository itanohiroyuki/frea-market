<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class RedirectAfterEmailVerification
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event)
    {
        // 認証完了後に飛ばしたいURLをセッションに保存
        session(['verified_redirect_to' => '/list']);
    }
}
