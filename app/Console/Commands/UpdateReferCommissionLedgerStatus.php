<?php
namespace App\Console\Commands;

use App\Models\Ledger;
use App\Models\RefferEarnsLedger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateReferCommissionLedgerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:referandcommissionledgerstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage User Refer & Commission Ledger status.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::statement("SET SQL_MODE = ''");

            $userslist = User::select('users.*', 'investments.id as invest_id', 'investments.user_id', 'investments.rate_of_intrest', 'investments.date', 'user_referrals.refer_id')
                ->leftJoin('investments', 'investments.user_id', '=', 'users.id')
                ->join('user_referrals', 'user_referrals.referral_id', '=', 'users.id')
                ->where('users.status', 1)
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
                $userInvestments = Ledger::where('user_id', $user->id)
                    ->whereNull('deleted_at')
                    ->sum('balance');

                $latestLedgerEntry = RefferEarnsLedger::where('user_id', $user->user_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestLedgerEntry && $user->refer_id == $latestLedgerEntry->user_id) {
                    $rateOfInterest  = $user->rate_of_intrest;
                    $previousBalance = $latestLedgerEntry->balance ?? 0;

                    // Convert the ledger date to Carbon instance
                    $ledgerDateCarbon = Carbon::parse($latestLedgerEntry->date);

                    // Get total number of days in the month and remaining days
                    $totalMonthDays = $ledgerDateCarbon->daysInMonth;
                    $ledgerDay      = $ledgerDateCarbon->day;
                    $remainingDays  = $totalMonthDays - $ledgerDay;

                    if ($remainingDays > 0) {
                        // Calculate daily interest and total interest amount
                        $dailyInterestRate = ($rateOfInterest / 100) / $totalMonthDays;
                        $interestAmount    = $userInvestments * $dailyInterestRate * $remainingDays;

                        // New balance after applying interest
                        $newBalance = $previousBalance + $interestAmount;

                        // Insert new ledger entry with calculated balance
                        RefferEarnsLedger::create([
                            'user_id'         => $user->refer_id,
                            'refer_id'        => $user->id,
                            'date'            => Carbon::now()->format('Y-m-d'),
                            'description'     => "Interest applied for {$remainingDays} days",
                            'rate_of_intrest' => $rateOfInterest,
                            'credit'          => $interestAmount,
                            'debit'           => 0,
                            'balance'         => $newBalance,
                        ]);

                        Log::info("Ledger updated for user ID: {$user->id}, Interest for {$remainingDays} days: {$interestAmount}, New Balance: {$newBalance}");
                    } else {
                        Log::info("No remaining days to apply interest for user ID: {$user->id}");
                    }
                } else {
                    Log::info("No valid ledger entry found for user ID: {$user->id}");
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Error updating ledger status: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}
