<?php

namespace App\Observers;

use App\Models\UserWithdrawRequest;

class UserWithdrawRequestObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\UserWithdrawRequest  $product
     * @return void
     */
    public function created(UserWithdrawRequest $user_withdrow)
    {
        /*$user_withdrow->voucher_no = 'VN-'.sprintf("%05d", $user_withdrow->id);
        $user_withdrow->save();*/
    }
}
