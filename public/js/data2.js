var controller = new Vue({
    el: '#controller',
    data: {
        datas: [],
        data: {},
        editStatus: false,
        validation: [],
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
            }).on('xhr', function () {
                _this.datas = _this.table.ajax.json().data;
            });
        },

        addData() {
            this.data = {};
            this.editStatus = false;
            this.validation = [];
            $('#modal-default').modal('show');
        },

        editData(event, row) {
            this.data = this.datas[row];
            this.editStatus = true;
            this.validation = [];
            $('#modal-default').modal('show');
        },
        
        submitForm(event, id) {
            event.preventDefault();
            const _this = this;
            let actionUrl = !this.editStatus ? this.actionUrl : this.actionUrl + '/' + id;
            axios.post(actionUrl, new FormData($(event.target)[0])).then(function (response) {
                if (response.data.success) {
                    $('#modal-default').modal('hide');
                    swal({
                        title: 'SUCCESS!',
                        text: response.data.message,
                        icon: 'success',
                        timer: 1000,
                        buttons: false,
                    }).then(function () {
                        _this.table.ajax.reload();
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
            }).then(function (isConfirm) {
                if (isConfirm) {
                    axios.post(this.actionUrl + '/' + id, {
                        _method: 'DELETE'
                    }).then(function (response) {
                        swal({
                            title: 'DELETED!',
                            text: 'Your Data Has Been Deleted!',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                            showCancelButton: false,
                            buttons: false,
                        }).then(function () {
                            _this.table.ajax.reload();
                        });
                    }).catch(function (error) {
                        swal({
                            title: 'ERROR!',
                            text: error,
                            icon: 'error',
                            timer: 1000,
                            showConfirmButton: false,
                            showCancelButton: false,
                            buttons: false,
                        }).then(function () {
                            _this.table.ajax.reload();
                        });
                    });
                } else {
                    swal("Cancelled", "Data is safe!", "error");
                }
            })
        },
    },

    mounted: function () {
        this.dataTable();
    },
});