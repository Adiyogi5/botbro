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

            $userslist = User::select('users.*', 'user_referrals.refer_id')
                ->join('user_referrals', 'user_referrals.referral_id', '=', 'users.id')
                ->where('users.status', 1)
                ->where('users.is_approved', 1)
                ->whereNull('users.deleted_at')
                ->groupBy('users.id')
                ->get();

            if ($userslist->isEmpty()) {
                Log::info('No eligible users found for ledger update.');
                return Command::SUCCESS;
            }

            $usersreferlist = User::select('users.*', 'user_referrals.refer_id')
                ->join('user_referrals', 'user_referrals.referral_id', '=', 'users.id')
                ->where('users.status', 1)
                ->where('users.is_approved', 1)
                ->whereNull('users.deleted_at')
                ->get();

            foreach ($userslist as $user) {
                $userReferrals = $usersreferlist->where('id', $user->id)->pluck('refer_id')->toArray();

                // Get the latest ledger balance for each user
                $userReferandCommissions = Ledger::whereIn('user_id', $userReferrals)
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('user_id')
                    ->map(function ($records) {
                        return $records->first()->balance; 
                    });

                $totalBalance = $userReferandCommissions->sum();

                // Get the correct rate of interest based on total balance
                $rateOfInterest = 0;
                foreach (COMMISSION as $commission) {
                    if ($commission['max'] === null || $totalBalance <= $commission['max']) {
                        $rateOfInterest = $commission['rate'];
                        break; 
                    }
                }

                ################### Average of All Date ###################
                ###########################################################
                $latestLedgerEntry = Ledger::whereIn('user_id', $userReferrals)
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('user_id');

                // Collect all dates from all users
                $allDates = $latestLedgerEntry->flatMap(function ($records) {
                    return $records->pluck('date')->map(function ($date) {
                        return strtotime($date); 
                    });
                });

                // Calculate the average timestamp
                $averageTimestamp = $allDates->avg();

                // Convert the average timestamp back to a date
                $averageDate = date('Y-m-d', $averageTimestamp);

                // Now, get the latest record date for each user
                $latestLedgerEntry = $latestLedgerEntry->map(function ($records) {
                    return $records->first()->date;
                });

                $commissionIntrestDay = [
                    'latestLedgerEntry' => $latestLedgerEntry,
                    'averageDate'       => $averageDate,
                ];

                ################### Check Legder Data and Give Intrest ###################
                ##########################################################################
                $latestrefercommissionLedgerEntry = RefferEarnsLedger::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
              
                if ($latestrefercommissionLedgerEntry && $user->id == $latestrefercommissionLedgerEntry->user_id) {
                    $previousBalance = $latestrefercommissionLedgerEntry->balance ?? 0;

                    // Convert the ledger date to Carbon instance
                    $ledgerDateCarbon = Carbon::parse($averageDate);
                    $totalMonthDays   = $ledgerDateCarbon->daysInMonth;
                    $ledgerDay        = $ledgerDateCarbon->day;
                    $remainingDays    = ($totalMonthDays - $ledgerDay) + 1;

                    if ($remainingDays > 0) {
                        // Calculate daily interest and total interest amount
                        $dailyInterestRate = ($rateOfInterest / 100) / $totalMonthDays;
                        $interestAmount    = $totalBalance * $dailyInterestRate * $remainingDays;

                        // New balance after applying interest
                        $newBalance = $previousBalance + $interestAmount;

                        // Insert new ledger entry with calculated balance
                        RefferEarnsLedger::create([
                            'user_id'         => $user->id,
                            'refer_id'        => 0,
                            'date'            => Carbon::now()->format('Y-m-d'),
                            'description'     => "Referral commission interest applied for {$remainingDays} days on the total Investment.",
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
