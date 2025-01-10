<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order; 
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateUserPurchaseStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:userpurchasestatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage user purchase status.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        // Update package purchase status if not clear
        $userpckage = User::where('status', 1)->where('purchase_status','!=',1)->get();

        if (!empty($userpckage)) {
            foreach ($userpckage as $key => $value) {
                try {  
                    $procount = Order::where('payment_status', 1)->where('order_status_id','!=',6)->where('user_id',$value->id)->get();
                    
                    if(count($procount) > 0){ 
                        // Update user purchase_status on purchses
                        User::where('id', $value->id)->update(['purchase_status' => 2]); 
                    }
                } catch (\Throwable $th) {
                    
                }
            }

        }
        return Command::SUCCESS;
    }
}
