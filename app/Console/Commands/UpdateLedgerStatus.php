<?php
namespace App\Console\Commands;

use App\Models\Ledger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateLedgerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ledgerstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage user ledger status.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::statement("SET SQL_MODE = ''");
            $userslist = User::select('users.*', 'investments.id as invest_id', 'investments.user_id', 'investments.rate_of_intrest', 'investments.date')
                ->leftJoin('investments', 'investments.user_id', '=', 'users.id')
                ->where('users.status', 1)
                ->where('users.is_approved', 1)
                ->whereNull('users.deleted_at')
                ->where('investments.payment_status', 1)
                ->where('investments.is_approved', 1)
                ->whereNull('investments.deleted_at')
                ->get();

            if ($userslist->isEmpty()) {
                Log::info('No eligible users found for ledger update.');
                return Command::SUCCESS;
            }

            foreach ($userslist as $user) {
                $latestLedgerEntry = Ledger::where('user_id', $user->user_id)
                    ->where('invest_id', $user->invest_id)
                    ->whereDate('date', '!=', Carbon::now()->format('Y-m-d'))
                    ->orderBy('created_at', 'desc')
                    ->first();
            
                if ($latestLedgerEntry && $latestLedgerEntry->balance > 0) {
                    $rateOfInterest  = $user->rate_of_intrest;
                    $previousBalance = $latestLedgerEntry->balance;
            
                    // Convert the ledger date to Carbon instance
                    $ledgerDateCarbon = Carbon::parse($latestLedgerEntry->date);
                    
                    // Get the total number of days in the month of the ledger date
                    $totalMonthDays = $ledgerDateCarbon->daysInMonth;
                    
                    // Get the day of the month from the ledger entry date
                    $ledgerDay = $ledgerDateCarbon->day;
                    
                    // Calculate remaining days in the month
                    $remainingDays = $totalMonthDays - $ledgerDay;
                   
                    if ($remainingDays > 0) {
                        // Calculate daily interest
                        $dailyInterestRate = ($rateOfInterest / 100) / $totalMonthDays;
                        $interestAmount    = $previousBalance * $dailyInterestRate * $remainingDays;
            
                        // New balance after applying interest
                        $newBalance = $previousBalance + $interestAmount;
            
                        // Insert new ledger entry with calculated balance
                        Ledger::create([
                            'user_id'         => $user->user_id,
                            'invest_id'       => $user->invest_id,
                            'date'            => Carbon::now()->format('Y-m-d'),
                            'description'     => "Interest applied for {$remainingDays} days",
                            'rate_of_intrest' => $rateOfInterest,
                            'credit'          => $interestAmount,
                            'debit'           => 0,
                            'balance'         => $newBalance,
                        ]);
            
                        Log::info("Ledger updated for user ID: {$user->user_id}, Interest for {$remainingDays} days: {$interestAmount}, New Balance: {$newBalance}");
                    } else {
                        Log::info("No remaining days to apply interest for user ID: {$user->user_id}");
                    }
                } else {
                    Log::info("No valid ledger entry found for user ID: {$user->user_id}");
                }
            }            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Error updating ledger status: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}
