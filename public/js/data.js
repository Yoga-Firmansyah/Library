const {
    createApp,
    ref,
    onMounted
} = Vue

var controller = createApp({
    setup() {
        const datas = ref([]);
        const data = ref({});
        const editStatus = ref(false);
        const validation = ref([]);
        const actionUrl = ref(this.actionUrl);
        const apiUrl = ref(this.apiUrl);


        function dataTable() {

            const _this = this;
            _this.table = $('#dataTable').DataTable({
                ajax: {
                    url: _this.apiUrl,
                    type: 'GET',
                },
                columns: columns,
            }).on('xhr', function () {
                datas.value = _this.table.ajax.json().data;
            });
        }

        function changeIt() {
            const _this = this;
            _this.table.ajax.reload();
        }

        function addData() {
            data.value = ref({});
            this.editStatus = ref(false);
            this.validation = ref([]);
            $('#modal-default').modal('show');

        }

        function editData(event, row) {
            data.value = this.datas[row];
            this.editStatus = ref(true);
            this.validation = ref([]);
            $('#modal-default').modal('show');
        }

        function submitForm(event, id) {
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
                        changeIt();
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
        }

        function deleteData(event, id) {
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
                            changeIt();
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
                            changeIt();
                        });
                    });
                } else {
                    swal("Cancelled", "Data is safe!", "error");
                }
            })
        }

        onMounted(() => {
            dataTable();
        });

        return {
            datas,
            data,
            validation,
            dataTable,
            editStatus,
            actionUrl,
            apiUrl,
            addData,
            editData,
            submitForm,
            deleteData,
        }
    }
}).mount('#controller')