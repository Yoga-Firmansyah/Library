<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Catalog;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $total_books = Book::count();
        $total_members = Member::count();
        $total_publishers = Publisher::count();
        $total_transactions = Transaction::whereMonth('date_start', date('m'))->count();

        $data_donut = Book::selectRaw('COUNT(publisher_id) AS total')
            ->groupBy('publisher_id')
            ->orderBy('publisher_id', 'ASC')
            ->pluck('total');

        $label_donut = Publisher::orderBy('publishers.id', 'ASC')
            ->join('books', 'books.publisher_id', '=', 'publishers.id')
            ->groupBy('publishers.id', 'publishers.name')
            ->pluck('publishers.name');

        $data_pie = Book::selectRaw('COUNT(catalog_id) AS total')
        ->groupBy('catalog_id')
        ->orderBy('catalog_id', 'ASC')
        ->pluck('total');

        $label_pie = Catalog::orderBy('catalogs.id', 'ASC')
        ->join('books', 'books.catalog_id', '=', 'catalogs.id')
        ->groupBy('catalogs.id', 'catalogs.name')
        ->pluck('catalogs.name');

        $label_bar = ['Borrow', 'Return'];
        $data_bar = [];

        foreach ($label_bar as $key => $value) {
            $data_bar[$key]['label'] = $label_bar[$key];
            $data_bar[$key]['backgroundColor'] = $key == 0 ? 'rgba(60, 141, 188, 0.9)' : 'rgba(210, 214, 222, 1)';
            $data_month = [];

            foreach (range(1, 12) as $month) {
                if ($key == 0) {
                    $data_month[] = Transaction::selectRaw("COUNT(*) as total")->whereMonth('date_start', $month)->first()->total;
                } else {
                    $data_month[] = Transaction::selectRaw("COUNT(*) as total")->whereMonth('date_end', $month)->first()->total;
                }
            }
            $data_bar[$key]['data'] = $data_month;
        }
        return view('home', compact('total_books', 'total_members', 'total_publishers', 'total_transactions', 'label_donut', 'data_donut', 'data_bar', 'label_pie', 'data_pie'));
    }

    public function test_spatie()
    {
        // $role = Role::create(['name' => 'operator']);
        // $permission = Permission::create(['name' => 'index transaction']);

        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);

        // $user = auth()->user();
        // $user->assignRole('operator');
        // return $user;

        // $user = User::with('roles')->get();
        // return $user;

        // $user = User::where('id', 2)->first();
        // $user->removeRole('operator');
    }

}
