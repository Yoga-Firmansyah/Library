@extends('layouts.admin')
@section('header', 'Author')
@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<div id="controller">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <a href="#" @click="addData()" class="btn btn-primary btn-sm">Add New Author</a>
                </div>
                <div class="container-fluid">
                    <div class=" card-body p-2">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10px">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Total Books</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal  -->
    <div class="modal fade" id="modal-default" aria-hidden="true" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <form :action="actionUrl" method="POST" enctype="multipart/form-data" @submit="submitForm($event, data.id)">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" v-if="editStatus">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" v-if="!editStatus">Add New Author</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-if="editStatus">Edit Author</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" :value="data.name"
                                placeholder="Enter Name"
                                class="form-control" required>
                            <div v-if="validation.name" class="mt-2 alert alert-danger">
                                @{{validation.name[0]}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" :value="data.email"
                                placeholder="Enter Email"
                                class="form-control" required>
                            <div v-if="validation.email" class="mt-2 alert alert-danger">
                                @{{ validation.email[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone_number" :value="data.phone_number"
                                placeholder="081xxxxxxxxx" pattern="[0-9]{10,14}"
                                class="form-control" required>
                            <div v-if="validation.phone_number" class="mt-2 alert alert-danger">
                                @{{ validation.phone_number[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" :value="data.address"
                                placeholder="Enter Address"
                                class="form-control" required>
                            <div v-if="validation.address" class="mt-2 alert alert-danger">
                                @{{ validation.address[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>
@endsection

@section('js')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Yajra DataTables -->
<script type="text/javascript">
    var actionUrl = '<?= url('authors'); ?>';
    var apiUrl = '<?= url('api/authors'); ?>';

    var columns = [{
            data: 'DT_RowIndex',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'name',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'email',
            class: 'text-center',
            orderable: false
        },
        {
            data: 'phone_number',
            class: 'text-center',
            orderable: false
        },
        {
            data: 'address',
            class: 'text-center',
            orderable: false
        },
        {
            data: 'date',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'books.length',
            class: 'text-center',
            orderable: true
        },
        {
            render: function(index, row, data, meta) {
                return `
                <div class=" d-flex justify-content-center">
                <button href="#" class="btn btn-warning btn-sm mr-2" onclick="controller.editData(event, ${meta.row})"><i class="fa fa-pencil-alt"></i></button>
                <button href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})"><i class="fa fa-trash"></i></button>
                </div>
                `;
            },
            class: 'text-center',
            orderable: false
        },
    ];
</script>

<script src="{{ asset('js/data2.js') }}"></script>

@endsection