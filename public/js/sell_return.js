$(document).ready(function() {
    //For edit pos form
    if ($('form#sell_return_form').length > 0) {
        pos_form_obj = $('form#sell_return_form');
    } else {
        pos_form_obj = $('form#add_pos_sell_form');
    }
    if ($('form#sell_return_form').length > 0 || $('form#add_pos_sell_form').length > 0) {
        initialize_printer();
    }

    //Date picker
    $('#transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });

    pos_form_validator = pos_form_obj.validate({
        submitHandler: function(form) {
            var cnf = true;

            if (cnf) {
                var data = $(form).serialize();
                var url = $(form).attr('action');
                $.ajax({
                    method: 'POST',
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function(result) {
                        if (result.success == 1) {
                            toastr.success(result.msg);
                            //Check if enabled or not
                            if (result.receipt.is_enabled) {
                                pos_print(result.receipt);
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
            return false;
        },
    });
});

function initialize_printer() {
    if ($('input#location_id').data('receipt_printer_type') == 'printer') {
        initializeSocket();
    }
}

function pos_print(receipt) {
    //If printer type then connect with websocket
    if (receipt.print_type == 'printer') {
        var content = receipt;
        content.type = 'print-receipt';

        //Check if ready or not, then print.
        if (socket.readyState != 1) {
            initializeSocket();
            setTimeout(function() {
                socket.send(JSON.stringify(content));
            }, 700);
        } else {
            socket.send(JSON.stringify(content));
        }

        setTimeout(function() {
            window.location.href = window.location.protocol + '//' + window.location.hostname + '/sells';
        }, 4000);

    } else if (receipt.html_content != '') {
        var title = document.title;
        if (typeof receipt.print_title != 'undefined') {
            document.title = receipt.print_title;
        }

        //If printer type browser then print content
        $('#receipt_section').html(receipt.html_content);
        __currency_convert_recursively($('#receipt_section'));

        setTimeout(function() {
            window.print();
            document.title = title;
        }, 1000);

        setTimeout(function() {
            window.location.href = window.location.protocol + '//' + window.location.hostname + '/sells';
        }, 4000);

    }
}

// //Set the location and initialize printer
// function set_location(){
// 	if($('input#location_id').length == 1){
// 	       $('input#location_id').val($('select#select_location_id').val());
// 	       //$('input#location_id').data('receipt_printer_type', $('select#select_location_id').find(':selected').data('receipt_printer_ty
// 	}

// 	if($('input#location_id').val()){
// 	       $('input#search_product').prop( "disabled", false ).focus();
// 	} else {
// 	       $('input#search_product').prop( "disabled", true );
// 	}

// 	initialize_printer();
// }

function disable_sell_return_form_actions(){
    if (!window.navigator.onLine) {
        return false;
    }
    $('#sell-return-form-submit').attr('disabled', 'true');
}

function enable_sell_return_form_actions(){
    $('#sell-return-form-submit').removeAttr('disabled');
}

$(document).on('change', 'input.input_quantity', function() {
    var inputed_quantity = parseFloat($(this).val());
    var error_msg_td = $(this).closest('tr').find('.input_quantity').closest('td');
    var total_returned = $(this).closest('tr').find('.total_returned').attr('data-total-returned');
    var total_quantity = $(this).closest('tr').find('.total_returned').attr('data-total-quantity');

    if (inputed_quantity < 0 || inputed_quantity == 0) {
        error_msg_td.find('label.error').remove();
        error_msg_td.append( '<label class="error "> Invalid quantity</label>');
        disable_sell_return_form_actions();
    } else if (total_quantity < (total_returned + inputed_quantity)) {
        error_msg_td.find('label.error').remove();
        error_msg_td.append( '<label class="error "> Invalid available quantity</label>');
        disable_sell_return_form_actions();
    } else {
        error_msg_td.find('label.error').remove();
        enable_sell_return_form_actions();
    }
});
