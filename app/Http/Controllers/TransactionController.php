<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.transaction.index');
    }

    public function api(Request $request)
    {
        $status = $request->status;
        $date_start = $request->date_start;

        if ($status == '0' || $status == '1') {
            $transaction = Transaction::with('member', 'transactionDetails')
                ->orderBy('transactions.status', 'ASC')
                ->orderBy('transactions.id', 'DESC')
                ->where('transactions.status', $status)
                ->get();
        } else if ($date_start) {
            $transaction = Transaction::with('member', 'transactionDetails')
                ->orderBy('transactions.status', 'ASC')
                ->orderBy('transactions.id', 'DESC')
                ->whereRaw('MONTH(transactions.date_start) = ' . $date_start)
                ->get();
        } else {
            $transaction = Transaction::with('member', 'transactionDetails')
                ->orderBy('transactions.status', 'ASC')
                ->orderBy('transactions.id', 'DESC')
                ->get();
        }

        $dataTables = datatables()->of($transaction)
            ->addColumn('borrowing_time', function ($transaction) {
                $date_start = Carbon::parse($transaction->date_start);
                $date_end = Carbon::parse($transaction->date_end);
                $days = $date_start->diffInDays($date_end);
                return convert_days($days);
            })
            ->addColumn('total_books', function ($transaction) {
                return $transaction->transactionDetails->sum('qty');
            })
            ->addColumn('total_price', function ($transaction) {
                $price = 0;
                foreach ($transaction->transactionDetails as $transactionDetails) {
                    $price += $transactionDetails->book->price;
                }
                return convert_price($price);
            })
            ->editColumn('status', function ($transaction) {
                return $transaction->status == '0' ? "Borrowing" : "Returned";
            })
            ->addIndexColumn();

        return $dataTables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $books = Book::where('qty', '!=', 0)->get();
        return view('admin.transaction.create', compact('members', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id'  => 'required|numeric',
            'date_start'  => 'required|date',
            'date_end'  => 'required|date',
            'books_id.*'  => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'member_id'   => $request->member_id,
            'date_start'   => $request->date_start,
            'date_end'   => $request->date_end,
            'status' => '0',
        ]);

        foreach ($request->books_id as $book_id) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'book_id' => $book_id,
                'qty' => 1,
            ]);

            $book = Book::find($book_id);
            $book->update([
                'qty' => $book->qty - 1,
            ]);
        }

        if ($transaction) {
            return redirect()->route('transactions.index')->with(['success' => 'Data Saved Successfully!']);
        } else {
            return redirect()->route('transactions.index')->with(['error' => 'Data Failed to Save!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction_details = TransactionDetail::whereRaw('transaction_id = ' . $transaction->id)->with('book')->get();
        $member = Member::find($transaction->member_id);
        return view('admin.transaction.show', compact('transaction', 'transaction_details', 'member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $members = Member::all();
        $books = Book::where('qty', '!=', 0)->get();
        $selectedBooks = TransactionDetail::selectRaw('book_id')->whereRaw('transaction_id = ' . $transaction->id)->get();

        if ($transaction->status == '1') {
            return redirect()->route('transactions.index')->with(['success' => 'Book Have Been Returned!']);
        } else {
            return view('admin.transaction.edit', compact('transaction', 'members', 'books', 'selectedBooks'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'member_id'  => 'required|numeric',
            'date_start'  => 'required|date',
            'date_end'  => 'required|date',
            'books_id.*'  => 'required|numeric',
            'status' =>
            [
                'required',
                Rule::in(['0', '1']),
            ],
        ]);

        $transaction->update([
            'member_id'   => $request->member_id,
            'date_start'   => $request->date_start,
            'date_end'   => $request->date_end,
            'status'   => $request->status,
        ]);

        if ($request->status == '0') {
            $transaction_details = TransactionDetail::whereRaw('transaction_id = ' . $transaction->id)->get();
            foreach ($transaction_details as $td) {
                $book = Book::find($td->book_id);
                $book->update([
                    'qty' => $book->qty + 1,
                ]);

                $td->delete();
            };

            foreach ($request->books_id as $book_id) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $book_id,
                    'qty' => 1,
                ]);

                $book = Book::find($book_id);
                $book->update([
                    'qty' => $book->qty - 1,
                ]);
            };
        } else {
            $transaction_details = TransactionDetail::whereRaw('transaction_id = ' . $transaction->id)->get();
            foreach ($transaction_details as $td) {
                $book = Book::find($td->book_id);
                $book->update([
                    'qty' => $book->qty + 1,
                ]);
            };
        }

        if ($transaction) {
            return redirect()->route('transactions.index')->with(['success' => 'Data Updated Successfully!']);
        } else {
            return redirect()->route('transactions.index')->with(['error' => 'Data Failed to Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction_details =  TransactionDetail::whereRaw('transaction_id = ' . $transaction->id)->get();
        foreach ($transaction_details as $td) {
            $book = Book::find($td->book_id);
            $book->update([
                'qty' => $book->qty + 1,
            ]);
        }

        $transaction->delete();


        if ($transaction) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
