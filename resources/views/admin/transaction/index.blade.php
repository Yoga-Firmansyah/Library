@extends('layouts.admin')
@section('header', 'Transaction')
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
                <div class="card-header row">
                    <div class="col d-flex justify-content-start">
                        <a href="{{route('transactions.create')}}" class="btn btn-primary btn-sm">Add Transaction</a>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <select name="status" class="form-select text-center col-6 mr-2">
                            <option value="9">Status Filter</option>
                            <option value="0">Borrowing</option>
                            <option value="1">Returned</option>
                        </select>
                        <select name="date_start" class="form-select text-center col-6">
                            <option value="0">Borrow Date Filter</option>
                            <option value="1"> January </option>
                            <option value="2"> February </option>
                            <option value="3"> March </option>
                            <option value="4"> April </option>
                            <option value="5"> May </option>
                            <option value="6"> June </option>
                            <option value="7"> July </option>
                            <option value="8"> Agustust </option>
                            <option value="9"> September </option>
                            <option value="10"> October </option>
                            <option value="11"> November </option>
                            <option value="12"> December </option>
                        </select>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="card-body p-2">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10px">#</th>
                                    <th class="text-center">Date Borrow</th>
                                    <th class="text-center">Date Return</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Borrowing Time<br />(Days)</th>
                                    <th class="text-center">Total Books</th>
                                    <th class="text-center">Total Price</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    var actionUrl = '<?= url('transactions'); ?>';
    var apiUrl = '<?= url('api/transactions'); ?>';

    var columns = [{
            data: 'DT_RowIndex',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'date_start',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'date_end',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'member.name',
            class: 'text-center',
            orderable: true
        },
        {
            data: 'borrowing_time',
            class: 'text-center',
            orderable: false
        },
        {
            data: 'total_books',
            class: 'text-center',
            orderable: false
        },
        {
            data: 'total_price',
            class: 'text-center',
            orderable: false
        },
        {
            data: 'status',
            class: 'text-center',
            orderable: true
        },
        {
            render: function(index, row, data, meta) {
                return `
                <div class=" d-flex justify-content-center">
                <a href="{{url('/transactions/${data.id}')}}" class="btn btn-info btn-sm mr-2"><i class="fa fa-search"></i></a>
                <a href="{{url('/transactions/${data.id}/edit')}}" class="btn btn-warning btn-sm mr-2"><i class="fa fa-pencil-alt"></i></a>
                <button href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})"><i class="fa fa-trash"></i></button>
                </div>
                `;
            },
            class: 'text-center',
            orderable: false
        },
    ];

    var controller = new Vue({
        el: '#controller',
        data: {
            datas: [],
            data: {},
            actionUrl,
            apiUrl,
        },

        methods: {
            dataTable() {
                const _this = this;
                _this.table = $('#dataTable').DataTable({
                    ajax: {
                        url: _this.apiUrl,
                        type: 'GET',
                    },
                    columns: columns,
                }).on('xhr', function() {
                    _this.datas = _this.table.ajax.json().data;
                });
            },
            deleteData(event, id) {
                event.preventDefault();
                const _this = this;
                swal({
                    title: "Are You Sure?",
                    text: "All Asociated With This Will Be Deleted!",
                    icon: "warning",
                    buttons: [
                        'Cancel',
                        'Yes, Delete It!'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        axios.post(this.actionUrl + '/' + id, {
                            _method: 'DELETE'
                        }).then(function(response) {
                            swal({
                                title: 'DELETED!',
                                text: 'Your Data Has Been Deleted!',
                                icon: 'success',
                                timer: 1000,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            }).then(function() {
                                _this.table.ajax.reload();
                            });
                        }).catch(function(error) {
                            swal({
                                title: 'ERROR!',
                                text: error,
                                icon: 'error',
                                timer: 1000,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            }).then(function() {
                                _this.table.ajax.reload();
                            });
                        });
                    } else {
                        swal("Cancelled", "Data is safe!", "error");
                    }
                })
            },
        },

        mounted: function() {
            this.dataTable();
        },
    });

    //Status Filter
    $('select[name=status]').on('change', function() {
        status = $('select[name=status]').val();
        console.log(status);
        if (status == 9) {
            controller.table.ajax.url(apiUrl).load();
            $('select[name=date_start]').val(0);
        } else {
            controller.table.ajax.url(apiUrl + '?status=' + status).load();
            $('select[name=date_start]').val(0);
        }
    });

    //Borrow Date Filter
    $('select[name=date_start]').on('change', function() {
        date_start = $('select[name=date_start]').val();
        if (date_start == 0) {
            controller.table.ajax.url(apiUrl).load();
            $('select[name=status]').val(9);
        } else {
            controller.table.ajax.url(apiUrl + '?date_start=' + date_start).load();
            $('select[name=status]').val(9);
        }
    });
</script>

@endsection