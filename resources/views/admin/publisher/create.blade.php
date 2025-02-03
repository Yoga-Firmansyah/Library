@extends('layouts.admin')
@section('header', 'Publisher')
@section('content')
<div class="row">
    <div class="col">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-plus mr-2"></i>Create New Publisher</h6>
            </div>

            <div class="card-body">
                <form action="{{ route('publishers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Enter Name"
                            class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                        <div class="invalid-feedback" style="display: block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="Enter Email"
                            class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                        <div class="invalid-feedback" style="display: block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="081xxxxxxxxx" pattern="[0-9]{10,14}"
                            class="form-control @error('phone_number') is-invalid @enderror">

                        @error('phone_number')
                        <div class="invalid-feedback" style="display: block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            placeholder="Enter Address"
                            class="form-control @error('address') is-invalid @enderror">

                        @error('address')
                        <div class="invalid-feedback" style="display: block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>Submit</button>
                        <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i>Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection