//Form Submition
function ajaxSubmit(e, form, callBackFunction) {

    if (form.valid()) {
        e.preventDefault();

        var action = form.attr('action');
        var form2 = e.target;
        var data = new FormData(form2);
        $.ajax({
            type: "POST",
            url: action,
            processData: false,
            contentType: false,
            dataType: 'json',
            data: data,
            success: function (response) {
                // var response = JSON.parse(response.status);
            // Décoder la partie status de la réponse
            var statusData = JSON.parse(response.status);
            $('input[name="' + response.csrf.csrfName + '"]').val(response.csrf.csrfHash);
                if (statusData.status) {
                    success_notify(statusData.notification);
                    if (form.attr('class') === 'ajaxDeleteForm') {
                        $('#alert-modal').modal('toggle')
                    } else {
                        $('#right-modal').modal('hide');
                    }
                    callBackFunction();
                } else {
                    error_notify(statusData.notification);
                }
                console.log()
                // Mettre à jour le jeton CSRF pour les futures soumissions
                

                setTimeout(() => {
                    if (response.refresh) {
                        window.location.reload()
                    }
                }, 3000);
            }
        });
    } else {
        error_required_field();
    }
}
