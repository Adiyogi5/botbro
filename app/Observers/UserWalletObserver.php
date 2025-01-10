<?php

namespace App\Observers;

use App\Models\UserWallet;

class UserWalletObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\UserWallet  $product
     * @return void
     */
    public function created(UserWallet $user_wallet)
    {
        $user_wallet->voucher_no = 'VN-'.sprintf("%05d", $user_wallet->id);
        $user_wallet->save();
    }
}
