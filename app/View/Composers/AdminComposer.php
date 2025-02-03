<?php
namespace App\View\Composers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminComposer
{
    public function compose(View $view): void
    {
        $current_date = Carbon::today()->toDateString();
        $transactions = Transaction::with('member')
            ->whereRaw("transactions.status = '0' ")
            ->whereRaw("transactions.date_end < " . "'" . $current_date . "'")
            ->get();

        foreach ($transactions as $key => $transaction) {
            $today = Carbon::today();
            $date_end = Carbon::parse($transaction->date_end);
            $late = convert_days($date_end->diffInDays($today));
            $transaction->late = late_return($transaction->member->name, $late);
        }

        $view->with('notif', $transactions);
    }

}