<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order; 
use App\Models\User; 
use App\Models\UserReferral; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateUserAgentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:useragentstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage user agent status.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        $userslist = User::where('status', 1)->get();

        if (!empty($userslist)) {
            foreach ($userslist as $key => $value) {
                try {  
                    $proQuantity = 0;
                    $refferUser = UserReferral::select('users.*')->leftJoin('users', 'users.id', '=', 'user_referrals.refer_id')->where('purchase_status','1')->where('referral_id',$value->id)->get();
                    if(!empty($refferUser) && count($refferUser)>=9){
                        User::where('id', $value->id)->update(['is_agent_allow' => 1]);
                    }                    

                } catch (\Throwable $th) {

                }
            }
        }

        return Command::SUCCESS;
    }
}
