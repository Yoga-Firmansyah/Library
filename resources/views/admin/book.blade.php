@extends('layouts.admin')
@section('header', 'Book')
@section('content')
<div id="controller">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="input-group mb-3 justify-content-between">

                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control mr-4" autocomplete="off" placeholder="Search title" v-model="search">

                        <button class="btn btn-primary" @click="addData()">Create New Book</button>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12" v-for="book in filterList">
                            <div class="info-box cursor" style="cursor: pointer;" v-on:click="editData(book)">
                                <div class="info-box-content">
                                    <span class="info-box-text h3">@{{ book.title }}</span>
                                    <span class="info-box-text">Qty: @{{ book.qty }}</span>
                                    <span class="info-box-number">@{{ book.price2 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  -->
    <div class="modal fade" id="modal-default" aria-hidden="true" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <form :action="actionUrl" method="POST" enctype="multipart/form-data" @submit="submitForm($event, book.id)">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" v-if="editStatus">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" v-if="!editStatus">Add New Book</h5>
                        <h5 class="modal-title" id="exampleModalLabel" v-if="editStatus">Edit Book</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>isbn</label>
                            <input type="text" inputmode="numeric" name="isbn" :value="book.isbn"
                                placeholder="Enter ISBN format (xxxxxxxxx) Numeric Only" minlength="9" maxlength="9"
                                class="form-control" required>
                            <div v-if="validation.isbn" class="mt-2 alert alert-danger">
                                @{{validation.isbn[0]}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" :value="book.title"
                                placeholder="Enter Title"
                                class="form-control" required>
                            <div v-if="validation.title" class="mt-2 alert alert-danger">
                                @{{validation.title[0]}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="text" name="year" :value="book.year"
                                placeholder="Enter Year" maxlength="4" inputmode="numeric"
                                class="form-control" required>
                            <div v-if="validation.year" class="mt-2 alert alert-danger">
                                @{{ validation.year[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <select class="form-select" name="publisher_id" :value="book.publisher_id">
                                <option value="" disabled>Select Publisher</option>
                                @foreach ($publishers as $publisher )
                                <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                                @endforeach
                            </select>
                            <div v-if="validation.publisher_id" class="mt-2 alert alert-danger">
                                @{{ validation.publisher_id[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <select class="form-select" name="author_id" :value="book.author_id" required>
                                <option value="" disabled>Select Author</option>
                                @foreach ($authors as $author )
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                            <div v-if="validation.author_id" class="mt-2 alert alert-danger">
                                @{{ validation.author_id[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Catalog</label>
                            <select class="form-select" name="catalog_id" :value="book.catalog_id" required>
                                <option value="" disabled>Select Catalog</option>
                                @foreach ($catalogs as $catalog )
                                <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                                @endforeach
                            </select>
                            <div v-if="validation.catalog_id" class="mt-2 alert alert-danger">
                                @{{ validation.catalog_id[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Qty Stock</label>
                            <input type="number" name="qty" :value="book.qty"
                                placeholder="Enter Qty Stock" min="0"
                                class="form-control" required>
                            <div v-if="validation.qty" class="mt-2 alert alert-danger">
                                @{{ validation.qty[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" :value="book.price"
                                placeholder="Enter Price" min="0"
                                class="form-control" required>
                            <div v-if="validation.price" class="mt-2 alert alert-danger">
                                @{{ validation.price[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div>
                            <button type="button" class="btn btn-danger" v-if="editStatus" v-on:click="deleteData(book.id)">Delete</button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary mr-2" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>
@endsection

@section('js')
<script type="text/javascript">
    var actionUrl = '<?= url('books'); ?>';
    var apiUrl = '<?= url('api/books'); ?>';

    var controller = new Vue({
        el: '#controller',
        data: {
            books: [],
            book: {},
            editStatus: false,
            validation: [],
            actionUrl,
            apiUrl,
            search: '',
        },

        methods: {
            getBooks() {
                const _this = this;
                $.ajax({
                    url: this.apiUrl,
                    type: 'GET',
                    success: function(book) {
                        _this.books = JSON.parse(book);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            },

            addData() {
                this.book = {};
                this.editStatus = false;
                this.validation = [];
                $('#modal-default').modal('show');
            },

            editData(data) {
                this.book = data;
                this.editStatus = true;
                this.validation = [];
                $('#modal-default').modal('show');
            },

            submitForm(event, id) {
                _this = this;
                event.preventDefault();
                let actionUrl = !this.editStatus ? this.actionUrl : this.actionUrl + '/' + this.book.id;
                axios.post(actionUrl, new FormData($(event.target)[0])).then(function(response) {
                    if (response.data.success) {
                        $('#modal-default').modal('hide');
                        swal({
                            title: 'SUCCESS!',
                            text: response.data.message,
                            icon: 'success',
                            timer: 1000,
                            buttons: false,
                        }).then(function() {
                            _this.getBooks();
                        });
                    }
                }).catch((error) => {
                    swal({
                        title: 'ERROR!',
                        icon: 'error',
                        timer: 1000,
                        buttons: false,
                    });
                    this.validation = error.response.data;
                    console.log(error)
                });
            },

            deleteData(event, id) {
                swal({
                    title: "Are You Sure?",
                    text: "All Data Asociated With This Will Be Deleted!",
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
                                $('#modal-default').modal('hide');
                                getBooks();
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
                                getBooks();
                            });
                        });
                    } else {
                        swal("Cancelled", "Data is safe!", "error");
                    }
                });
            },

        },

        mounted: function() {
            this.getBooks();
        },

        computed: {
            filterList() {
                return this.books.filter(book => {
                    return book.title.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        }
    });
</script>

@endsection