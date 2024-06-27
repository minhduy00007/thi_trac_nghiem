<script type="text/javascript">
function initDataTable($table, $url, $columns, $method="GET") {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: $url,
            type: $method,
            data: { _token: "{{ csrf_token() }}"}
        },
        columns: $columns,
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, 'Tất cả'],
        ],
        pageLength: 25,
        pagingType: "full_numbers",
        fixedHeader: true,
        language: {
            zeroRecords: "Không có dữ liệu...",
            info: "Dòng _START_ đến _END_ / _TOTAL_",
            infoEmpty: "",
            infoFiltered:   "",
            search: "Tìm kiếm",
            paginate: {
                first: "<i class='fa fa-angle-double-left'></i>",
                previous: "<i class='fa fa-angle-left'></i>",
                next: "<i class='fa fa-angle-right'></i>",
                last: "<i class='fa fa-angle-double-right'></i>"
            },
            lengthMenu:     "Hiển thị _MENU_ dòng",
            loadingRecords: "Đang tải dữ liệu...",
        },
        drawCallback: function(settings) {
            var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
            pagination.toggle(this.api().page.info().pages > 1);
        }
    });
}

function onDeleteRow($dataTable, $alert, $url, $data) {
    Swal.fire({   
        title: $alert.title,   
        text: $alert.text,   
        showCancelButton: true,   
        confirmButtonText: "Đồng ý",   
        cancelButtonText: "Huỷ bỏ",
        confirmButtonColor: "#2eacb3",
        cancelButtonColor: "#fc5a69"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: $url,
                method: "DELETE",
                data: $data
            })
            .done(function(response) {
                if (response.success) {
                    // toastr.success(response.message, "", { positionClass: 'toastr toast-top-right', containerId: 'toast-top-right' });
                    // $dataTable.ajax.reload(null, false);
                    window.location.replace(response.redirect);
                } else {
                    Swal.fire({   
                        text: response.message,   
                        type: "error"
                    });
                }
            });
        }
    });
}

function onOpenModal($modal, $form) {
    $modal.modal({
        dismissible: false,
        onCloseEnd: function() { 
            $form.trigger("reset");
            // $validateForm.resetForm();
        }
    });
    $modal.modal("show");
}
</script>