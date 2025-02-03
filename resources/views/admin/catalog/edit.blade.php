@extends('layouts.admin')
@section('header', 'Catalog')
@section('content')
<div class="row">
    <div class="col">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-pencil-alt mr-2"></i>Edit Catalog</h6>
            </div>

            <div class="card-body">
                <form action="{{ route('catalogs.update', $catalog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', $catalog->name) }}"
                            placeholder="Enter Name"
                            class="form-control @error('name') is-invalid @enderror">

                        @error('name')
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