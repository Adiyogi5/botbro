<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class OrderCancel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ordercancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Orders cancel if order amount not paid in 24 hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Update package payment status if not clear
        try{
            $unpaid_orders = Order::where('payment_status',0)->where('order_status_id',1)->where('deleted_at',Null)->where('created_at', '<', Carbon::now()->subHours(24)->toDatetimeString())->update(['order_status_id'=>6]);

        } catch (\Throwable $th) {
                        
        }
        
        return Command::SUCCESS;
    }
}
