@extends('layouts.admin')
@section('header', 'Transaction')
@section('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css') }}">
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-plus mr-2"></i>Add Transaction</h6>
            </div>

            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Member</label>
                        <div class="col-md-8">
                            <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" required>
                                <option value="" selected disabled>Select Member</option>
                                @foreach ($members as $member )
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Date Borrow</label>
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between">
                                <input type="date" name="date_start"
                                    class="form-control col-5 @error('date_start') is-invalid @enderror" required>
                                <span class="col-2 text-center">&#8722;</span>
                                <input type="date" name="date_end"
                                    class="form-control col-5 @error('date_end') is-invalid @enderror" required>
                            </div>
                            @error('date_start')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('date_end')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Books</label>
                        <div class="col-md-8">
                            <select class="select2 @error('books_id') is-invalid @enderror" multiple="multiple" name="books_id[]" style="width: 100%;" required>
                                @foreach ($books as $book )
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                            @error('books_id')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>Submit</button>
                        <a class="btn btn-secondary" href="{{route('transactions.index')}}"><i class="fa fa-times"></i>Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            placeholder: 'Select Books',
        });
    });
</script>
@endsection