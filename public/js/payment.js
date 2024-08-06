$(document).ready(function() {
    $(document).on('click', '.add_payment_modal', function(e) {
        e.preventDefault();
        var container = $('.payment_modal');

        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function(result) {
                if (result.status == 'due') {
                    container.html(result.view).modal('show');
                    __currency_convert_recursively(container);
                    $('#paid_on').datetimepicker({
                        format: moment_date_format + ' ' + moment_time_format,
                        ignoreReadonly: true,
                    });
                    container.find('form#transaction_payment_add_form').validate();
                    set_default_payment_account();

                    $('.payment_modal')
                        .find('input[type="checkbox"].input-icheck')
                        .each(function() {
                            $(this).iCheck({
                                checkboxClass: 'icheckbox_square-blue',
                                radioClass: 'iradio_square-blue',
                            });
                        });
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', '.edit_payment', function(e) {
        e.preventDefault();
        var container = $('.edit_payment_modal');

        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function(result) {
                container.html(result).modal('show');
                __currency_convert_recursively(container);
                $('#paid_on').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
                container.find('form#transaction_payment_add_form').validate();
            },
        });
    });

    $(document).on('click', '.view_payment_modal', function(e) {
        e.preventDefault();
        var container = $('.payment_modal');

        $.ajax({
            url: $(this).attr('href'),
            dataType: 'html',
            success: function(result) {
                $(container)
                    .html(result)
                    .modal('show');
                __currency_convert_recursively(container);
            },
        });
    });
    $(document).on('click', '.delete_payment', function(e) {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_payment,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    url: $(this).data('href'),
                    method: 'delete',
                    dataType: 'json',
                    success: function(result) {
                        if (result.success === true) {
                            $('div.payment_modal').modal('hide');
                            $('div.edit_payment_modal').modal('hide');
                            toastr.success(result.msg);
                            if (typeof purchase_table != 'undefined') {
                                purchase_table.ajax.reload();
                            }
                            if (typeof sell_table != 'undefined') {
                                sell_table.ajax.reload();
                            }
                            if (typeof expense_table != 'undefined') {
                                expense_table.ajax.reload();
                            }
                            if (typeof ob_payment_table != 'undefined') {
                                ob_payment_table.ajax.reload();
                            }
                            // project Module
                            if (typeof project_invoice_datatable != 'undefined') {
                                project_invoice_datatable.ajax.reload();
                            }
                            
                            if ($('#contact_payments_table').length) {
                                get_contact_payments();
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //view single payment
    $(document).on('click', '.view_payment', function() {
        var url = $(this).data('href');
        var container = $('.view_modal');
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'html',
            success: function(result) {
                $(container)
                    .html(result)
                    .modal('show');
                __currency_convert_recursively(container);
            },
        });
    });

    $(document).on('click', '.get-mpesa-payment', function () {
        let business_id = $('#pos-business-id').val();
        let payment_amount = $('#amount').val();
        swal({
            title: 'Waiting',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(confirm => {
            if (confirm) {
                generateMpesaRequest(business_id, payment_amount);
            }
        });
    });

    $(document).on('change', '#amount', function() {
        var payment_type = $('#transaction_payment_add_form .payment_types_dropdown').val();
        if (payment_type && payment_type == 'mpesa') {
            var amount = parseFloat($(this).val());
            $('.get-mpesa-payment').attr('data-amount', amount.toFixed(2));
            $('.mpesa-button-amount').text(amount.toFixed(2));
        }
    });
});

$(document).on('change', '#transaction_payment_add_form .payment_types_dropdown', function(e) {
    set_default_payment_account();
});

function set_default_payment_account() {
    var default_accounts = {};

    if (!_.isUndefined($('#transaction_payment_add_form #default_payment_accounts').val())) {
        default_accounts = JSON.parse($('#transaction_payment_add_form #default_payment_accounts').val());
    }

    var payment_type = $('#transaction_payment_add_form .payment_types_dropdown').val();
    if (payment_type && payment_type != 'advance') {
        var default_account = !_.isEmpty(default_accounts) && default_accounts[payment_type]['account'] ? 
            default_accounts[payment_type]['account'] : '';
        $('#transaction_payment_add_form #account_id').val(default_account);
        $('#transaction_payment_add_form #account_id').change();
    }
    
    if (payment_type && payment_type == 'mpesa') {
        var business_id = $('#pos-business-id').val();
        var payment_amount = $('#amount').val();
        var mpesaButton = `<div class="col-md-4 get-mpesa-paymet-div" style="margin-top: 25px;">
                                <a class="btn btn-warning get-mpesa-payment" data-amount="${payment_amount}" data-businessId="${business_id}">Get <span class="mpesa-button-amount">${payment_amount}</span> using mpesa</a>
                            </div>`;
        $('.amount-row').after(mpesaButton);  
        $('.btn-primary').hide();
    } else {
        if ($('.get-mpesa-paymet-div').length > 0) {
            $('.get-mpesa-paymet-div').remove();
        }
        $('.btn-primary').show();
    }
}

function generateMpesaRequest(business_id, amount, passed_by = null)
{
    $.ajax({
        method: 'GET',
        url: base_path + '/mpesa-check-payments' + '?business_id=' + business_id + '&amount=' + amount + '&passed_by=' + passed_by,
        dataType: 'json',
        success: function(element) {
            if (element.data && element.data != null && element.data != 'undefined') {
                    showPaymentMessage(business_id, amount, element.data);
            } else {
                swal('Mpesa did not grab any payment yet.');
            }
            
        },
    });
}

function showPaymentMessage(business_id, amount, element)
{
    let name = element.first_name;
    if (element.middle_name != null) {
        name += ' ' + element.middle_name;
    }
    if (element.last_name != null) {
        name += ' ' + element.last_name;
    }
    let message = `${name} made a payment of ${element.transaction_amount} KSh and transaction id = ${element.transaction_id}`;
    swal({
        title: LANG.sure,
        text: message,
        icon: "warning",
        buttons: {
            cancel: "Cancel",
            confirm: "Confirm"
        },
        dangerMode: true,
    }).then((confirmed) => {
        if (confirmed) {
            captureMpesaPaymentForCashier(business_id, amount, element);
            $(document).ready(function () {
                $('.btn-primary').show();
            });
        } else {
            $(document).ready(function () {
                $('.btn-primary').hide();
            });
            generateMpesaRequest(business_id, amount, element.id);
        }
    });
}

function captureMpesaPaymentForCashier(business_id, amount, element)
{
    $.ajax({
        method: 'POST',
        url: base_path + '/mpesa-capture',
        dataType: 'json',
        data: {
            business_id : business_id,
            amount : amount,
            transaction_id: element.id
        },
        success: function(element) {
            swal('Payment captured successfully');
        },
    });
}

$(document).on('change', '.payment_types_dropdown', function(e) {
    var payment_type = $('#transaction_payment_add_form .payment_types_dropdown').val();
    account_dropdown = $('#transaction_payment_add_form #account_id');
    if (payment_type == 'advance') {
        if (account_dropdown) {
            account_dropdown.prop('disabled', true);
            account_dropdown.closest('.form-group').addClass('hide');
        }
    } else {
        if (account_dropdown) {
            account_dropdown.prop('disabled', false); 
            account_dropdown.closest('.form-group').removeClass('hide');
        }    
    }
});

$(document).on('submit', 'form#transaction_payment_add_form', function(e){
    var is_valid = true;
    var payment_type = $('#transaction_payment_add_form .payment_types_dropdown').val();
    var denomination_for_payment_types = JSON.parse($('#transaction_payment_add_form .enable_cash_denomination_for_payment_methods').val());
    if (denomination_for_payment_types.includes(payment_type) && $('#transaction_payment_add_form .is_strict').length && $('#transaction_payment_add_form .is_strict').val() === '1' ) {
        var payment_amount = __read_number($('#transaction_payment_add_form .payment_amount'));
        var total_denomination = $('#transaction_payment_add_form').find('input.denomination_total_amount').val();
        if (payment_amount != total_denomination ) {
            is_valid = false;
        }
    }

    $('#transaction_payment_add_form').find('button[type="submit"]')
            .attr('disabled', false);

    if (!is_valid) {
        $('#transaction_payment_add_form').find('.cash_denomination_error').removeClass('hide');
        e.preventDefault();
        return false;
    } else {
        $('#transaction_payment_add_form').find('.cash_denomination_error').addClass('hide');
    }
    
})