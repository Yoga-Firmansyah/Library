@extends('layouts.admin')
@section('header', 'Publisher')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <a href="{{route('publishers.create')}}" class="btn btn-primary btn-sm">Add New Publisher</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10px">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Total Books</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publishers as $key => $publisher)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$publisher->name}}</td>
                            <td>{{$publisher->email}}</td>
                            <td>{{$publisher->phone_number}}</td>
                            <td>{{$publisher->address}}</td>
                            <td>{{count($publisher->books)}}</td>
                            <td class="text-center">
                                <div class=" d-flex justify-content-center">
                                    <a href="{{ route('publishers.edit', $publisher->id) }}"
                                        class="btn btn-sm btn-primary mr-2">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <button onClick="Delete(this.id)" class="btn btn-sm btn-danger"
                                        id="{{ $publisher->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    //ajax delete
    function Delete(id) {
        var id = id;
        var token = $("meta[name='csrf-token']").attr("content");
        swal({
            title: "Are You Sure?",
            text: "All Books With This Publisher Will Be Deleted!",
            icon: "warning",
            buttons: [
                'Cancel',
                'Yes, Delete It!'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                //ajax delete
                jQuery.ajax({
                    url: "{{url('publishers')}}/" + id,
                    data: {
                        "id": id,
                        "_token": token
                    },
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status == "success") {
                            swal({
                                title: 'DELETED!',
                                text: 'Your Data Has Been Deleted!',
                                icon: 'success',
                                timer: 1000,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            swal({
                                title: 'ERROR!',
                                text: 'Your Data Is Not Deleted!',
                                icon: 'error',
                                timer: 1000,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            }).then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            } else {
                return true;
            }
        })
    }
</script>
@endsection