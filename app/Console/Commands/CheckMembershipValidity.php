<?php
namespace App\Console\Commands;

use App\Models\MembershipDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckMembershipValidity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:membershipvalidity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage User Membership Validity.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::statement("SET SQL_MODE = ''");

            $userslist = User::select(
                'users.*',
                'membership_details.id as membership_id',
                'membership_details.user_id',
                'membership_details.membership_start_date',
                'membership_details.membership_end_date'
            )
                ->leftJoin('membership_details', 'membership_details.user_id', '=', 'users.id')
                ->where('users.status', 1)
                ->whereNull('users.deleted_at')
                ->whereNull('membership_details.deleted_at')
                ->get();
            
            if ($userslist->isEmpty()) {
                Log::info('All users membership validity is up to date.');
                return Command::SUCCESS;
            }

            foreach ($userslist as $user) {
                
                if (($user->membership_end_date) < today()->format('Y-m-d')) {

                    $membershipValidity = MembershipDetail::where('user_id', $user->id)
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($membershipValidity) {
                        $membershipValidity->update([
                            'deleted_at' => Carbon::now(),
                        ]);

                        User::where('id', $user->id)->update([
                            'is_approved' => 0,
                        ]);

                        Log::info("Membership validity updated for user ID: {$user->id}");
                    } else {
                        Log::info("No valid membership entry found for user ID: {$user->id}");
                    }
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Error updating membership validity: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
