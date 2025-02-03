@extends('layouts.admin')
@section('header', 'Transaction')
@section('content')
<div class="row">
    <div class="col">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-search mr-2"></i>Detail Transaction</h6>
            </div>

            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Member</label>
                    <div class="col-md-8">
                        <h6>{{$member->name}}</h6>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Date Borrow</label>
                    <div class="col-md-8">
                        <div class="d-flex ">
                            <h6>{{ $transaction->date_start }} - {{ $transaction->date_end }}</h6>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Books</label>
                    <div class="col-md-8">
                        @foreach ($transaction_details as $key => $td )
                        <h6>{{$key+1}}. {{ $td->book->title }}</h6>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Status</label>
                    <div class="col-md-8">
                        <h6>{{$transaction->status == '0' ? 'Not Yet Returned!' : 'Already Returned!'}}</h6>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a class="btn btn-secondary" href="{{route('transactions.index')}}">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection