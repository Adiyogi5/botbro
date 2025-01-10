<?php

namespace App\Console\Commands;

use App\Models\BadgeMaster;
use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Returns;
use App\Models\RewardMaster;
use App\Models\User;
use App\Models\UserBadgeLog;
use App\Models\UserRewardLog;
use App\Models\UserTempWallet;
use App\Models\UserWallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateBadgeReward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:badgereward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage wallet amount after delivery days, once the return time completed.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 
        // Update package payment status if not clear
        $userpckage = User::where('status', 1)->get();
        $date = date('Y-m-d');
        if (!empty($userpckage)) {
            foreach ($userpckage as $key => $value) {
                try {  
                    $proQuantity = 0;
                    $procount = Order::select('quantity')->leftJoin('order_products', 'order_products.order_id', '=', 'orders.id')->where('payment_status', 1)->where('order_status_id',5)->where('user_id',$value->id)->where('is_return_apply',0)->whereRaw('order_return_expiredate < "'.Carbon::now()->toDateString().'"')->get();

                    foreach ($procount as $pkey => $pvalue) {
                        $proQuantity= $proQuantity+$pvalue['quantity'];
                    }  
                    
                    if(count($procount) > 0){
                        // update user purchase_status
                        User::where('id', $value->id)->update(['purchase_status' => 1]); 

                        // Update Badge
                        $badgeData = BadgeMaster::select('badge_masters.*','user_badge_logs.badge_id as badge_log_id')->leftJoin("user_badge_logs",function($join) use($value){
                            $join->on("user_badge_logs.badge_id","=","badge_masters.id")
                                ->where("user_badge_logs.user_id","=",$value->id);
                        })->where('min_product', '<=',$proQuantity)->where('status',1)
                        ->orderBy('badge_masters.id','desc')->first();

                        if(!empty($badgeData)){                
                            if($badgeData->id > $badgeData->badge_log_id){
                                UserBadgeLog::create([
                                    'user_id'           => $value->id,
                                    'badge_id'          => $badgeData['id'],
                                    'date'              => date('Y-m-d H:i:s'),
                                    'purchase_count'    => $proQuantity,
                                    'particulars'       => 'Congratulations, Your are upgrad at '.$badgeData['name'].' on completion of '.$proQuantity.' product purchasing!!',
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
                                ]);
                                // Update user badge record
                                User::where('id', $value->id)->update(['badge_status' => $badgeData['id']]); 
                            }
                        }
                        
                        // Update Reward
                        $rewardData = RewardMaster::select('reward_masters.*','user_reward_logs.reward_id as reward_log_id')->leftJoin("user_reward_logs",function($join) use($value){
                            $join->on("user_reward_logs.reward_id","=","reward_masters.id")
                                ->where("user_reward_logs.user_id","=",$value->id);
                        })->where('min_product', '<=',$proQuantity)->where('status',1)
                        ->orderBy('reward_masters.id','desc')->first();
                        if(!empty($rewardData)){
                            if($rewardData->id > $rewardData->reward_log_id){
                                UserRewardLog::create([
                                    'user_id'           => $value->id,
                                    'reward_id'         => $rewardData['id'],
                                    'reward_name'       => $rewardData['name'],
                                    'date'              => date('Y-m-d H:i:s'),
                                    'reward_status'     => 0,
                                    'purchase_count'    => $proQuantity,
                                    'particulars'       => 'Congratulations, You are getting reward on completion of '.$proQuantity.' product purchasing!!',
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    
                }
            }

        }
        return Command::SUCCESS;
    }
}
