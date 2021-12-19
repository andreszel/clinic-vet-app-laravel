$(function() {
    $('.delete-medical').click(function() {
        Swal.fire({
            title: 'Czy na pewno chcesz usunąć lek?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Tak',
            cancelButtonText: 'Anuluj'
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "DELETE",
                    url: deleteUrlMedical,
                    data: {
                    }
                })
                .done(function(data){
                    $('#validation-message-medical').append('<div class="alert dark alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>' + data.message + '</b></div>');
                    setTimeout(function () { window.location.reload(true); }, 2500);
                })
                .fail(function(data){
                    Swal.fire('Oops...', data.responseJSON.message, 'error');
                });
            }
        });
    });
});