<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Catalog;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // No. 1
        $data = Member::select('*')
            ->join('users', 'users.member_id', '=', 'members.id')
            ->get();

        //No. 2
        $data2 = Member::select('*')
            ->leftJoin('users', 'users.member_id', '=', 'members.id')
            ->where('users.id', NULL)
            ->get();

        //No. 3
        $data3 = Transaction::select('members.id', 'members.name')
            ->rightJoin('members', 'members.id', '=', 'transactions.member_id')
            ->where('transactions.member_id', NULL)
            ->get();

        //No. 4
        $data4 = Member::select('members.id', 'members.name', 'members.phone_number')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->orderBy('member_id', 'asc')
            ->get();

        // No. 5
        $data5 = Member::selectRaw('count(transactions.member_id) as total_pinjam, members.id, members.name, members.phone_number')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->groupBy('members.id')
            ->havingRaw('COUNT(transactions.member_id) > 1')
            ->get();

        // No. 6
        $data6 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->orderBy('transactions.date_start', 'ASC')
            ->get();

        // No. 7
        $data7 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->whereRaw('MONTH(transactions.date_end) = "10"')
            ->get();

        // No. 8
        $data8 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->whereRaw('MONTH(transactions.date_start) = "08"')
            ->get();

        // No. 9
        $data9 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->whereRaw('MONTH(transactions.date_start) = "08" AND MONTH(transactions.date_end) = "10"')
            ->get();

        // No. 10
        $data10 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->whereRaw('members.address LIKE "%Ngawi%"')
            ->get();

        // No. 11
        $data11 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->whereRaw('members.address LIKE "%Ngawi%" AND members.gender LIKE "%L%"')
            ->get();

        // No. 12
        $data12 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end, books.isbn, transaction_details.qty')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('books', 'books.id', '=', 'transaction_details.book_id')
            ->whereRaw('transaction_details.qty > 1')
            ->get();

        // No. 13
        $data13 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end, books.isbn, transaction_details.qty, books.price, books.price*transaction_details.qty AS total')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('books', 'books.id', '=', 'transaction_details.book_id')
            ->get();

        // No. 14
        $data14 = Member::selectRaw('members.name, members.phone_number, members.address, transactions.date_start, transactions.date_end, books.isbn, transaction_details.qty, books.title, publishers.name AS publisher, authors.name AS author, catalogs.name AS catalog')
            ->join('transactions', 'transactions.member_id', '=', 'members.id')
            ->join('transaction_details', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('books', 'books.id', '=', 'transaction_details.book_id')
            ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->join('catalogs', 'catalogs.id', '=', 'books.catalog_id')
            ->get();

        // No. 15
        $data15 = Catalog::selectRaw('catalogs.*, books.title')
        ->join('books', 'books.catalog_id', '=', 'catalogs.id')
        ->get();

        // No. 16
        $data16 = Book::selectRaw('books.*, publishers.name')
        ->leftJoin('publishers', 'publishers.id', '=', 'books.publisher_id')
        ->get();

        // No. 17
        $data17 = Book::selectRaw('COUNT(author_id)')
        ->whereRaw('author_id = 1')
        ->get();

        // No. 18
        $data18 = Book::selectRaw('*')
        ->whereRaw('price > 50000')
        ->get();

        // No. 19
        $data19 = Book::selectRaw('*')
        ->whereRaw('publisher_id = 4 AND qty > 10')
        ->get();

        // No. 20
        $data20 = Member::selectRaw('*')
        ->whereRaw('MONTH(created_at) = "08"')
        ->get();

        return $data20;
        return view('home');
    }
}
