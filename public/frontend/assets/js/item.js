$((e) => {
    $("#quantity, #rate").on('keyup', function() {
        var quantity = $('#quantity').val();
        var rate = $('#rate').val();
        var current_value = rate * quantity;
        $('#amount').val(current_value.toFixed(2));
    });

    //COPY FTOM ONLINE
    $('.add-item').on('click', function() {
        var item_id = $('#item_id').val();
        var quantity = $('#quantity').val();
        var amount = $('#amount').val();
        var rate = $('#rate').val();
        var freight = $('#freight_charge').val() == '' ? 0 : $('#freight_charge').val();
        var disc = $('#disc_rate').val() == '' ? 0 : $('#disc_rate').val();
        var account = $('#accountName').val();
        var chart_id = $('#chart_id').val();
        var tax = $('#is_tax').val();
        var item_name = $('#item_id option:selected').html();
        var item_reg_name = $('#item_reg_name').val();

        if (item_id == '') {
            toast('warning', 'Please Seletec An Item');
            $('#item_id').focus();
            return false;
        }
        if (quantity == '') {
            toast('warning', 'Please enter quantity');
            $('#quantity').focus();
            return false;
        }
        if (amount == '') {
            toast('warning', 'Please enter amount');
            $('#amount').focus();
            return false;
        }
        if (account == '') {
            toast('warning', 'Income Account Tax');
            $('#accountName').focus();
            return false;
        }
        if (tax == '') {
            toast('warning', 'Income Tax');
            $('#is_tax').focus();
            return false;
        }
        var totalamount = gst_total = amount;
        var gst = trate = disc_amount = 0;
        if (disc != '') {
            disc_amount = totalamount * (disc / 100);
            totalamount = gst_total = (totalamount - (totalamount * (disc / 100)));
        }
        if (freight != '') {
            totalamount = gst_total = parseFloat(freight) + parseFloat(totalamount);
        }
        if (tax == 'yes') {
            totalamount = parseFloat(totalamount) + (totalamount * 0.1);
            gst = gst_total * 0.1;
            trate = '10.00';
        } else {
            trate = '0.00';
        }
        var html = '<tr>';
        html += '<tr class="trData"><td class="serial"></td><td>' + item_name + '</td><td>' + parseFloat(quantity).toFixed(2) + '</td><td>' + parseFloat(rate).toFixed(2) + '</td><td>' + parseFloat(amount).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) + '</td><td class="text-right">' + parseFloat(freight).toFixed(2) + '</td><td class="text-right">' + account + '</td><td class="text-right">' + trate + '</td><td class="text-right">' + parseFloat(totalamount).toFixed(2) + '</td><td align="center">';
        html += '<input type="hidden" name="inv_id[]">';
        html += '<input type="hidden" name="item_id[]" value="' + item_id + '" />';
        html += '<input type="hidden" name="item_name[]" value="' + item_name + '" />';
        html += '<input type="hidden" name="amount[]" value="' + amount + '" />';
        html += '<input type="hidden" name="quantity[]" value="' + quantity + '" />';
        html += '<input type="hidden" name="rate[]" value="' + rate + '" />';
        html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
        html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
        html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
        // html += '<input type="hidden" name="account[]" value="' + account + '" />';
        html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
        if (tax == 'yes') {
            html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
        } else {
            html += '<input type="hidden" name="gst_amt[]" value="0" />';
        }
        html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
        html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
        html += '<input type="hidden" name="item_reg_name[]" value="' + item_reg_name + '" />';
        html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
        toast('success', 'Added');
        $('.item-table tbody').append(html);
        $('#item_id').val('');
        $('#job_des').val('');
        $('#amount').val('');
        $('#disc_rate').val('');
        $('#freight_charge').val('');
        $('#quantity').val('');
        $('#rate').val('');
        serialMaintain();
    });

    $('.item-table').on('click', '.item-delete', function(e) {
        var element = $(this).parents('tr');
        element.remove();
        toast('warning', 'item removed!');
        e.preventDefault();
        serialMaintain();
    });

    function serialMaintain() {
        var i = 1;
        var subtotal = gst_amt_subtotal = 0;
        $('.serial').each(function(key, element) {
            $(element).html(i);
            var total = $(element).parents('tr').find('input[name="totalamount[]"]').val();
            var gst_amt = $(element).parents('tr').find('input[name="gst_amt[]"]').val();
            subtotal += +parseFloat(total);
            gst_amt_subtotal += +parseFloat(gst_amt);
            i++;
        });
        $('.sub-total').html(subtotal.toFixed(2));
        $('#total_amount').val(subtotal);
        $('#gst_amt_subtotal').val(gst_amt_subtotal);
    };

    function bankamount() {
        $("#payment_amount").removeAttr('disabled', 'disabled')
    }
    $("#store").on('submit', function(e) {
        e.preventDefault();
        let data = $(this).serialize();
        let url = $(this).attr('action');
        let method = $(this).attr('method');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: res => {
                if (res.status == 200) {
                    $(".trData").remove();
                    $(".sub-total").html('$ 0.00')
                    $("#payment_amount").val('0.00')
                    $("#inv_no").val(res.inv_no.toString().padStart(8, '0'));
                    toast('success', res.message);
                } else {
                    toast('error', res.message);
                }
            },
        });
    });
    $("#item_id").on('change', function() {
        var item_id = $(this).val();
        if (item_id == 'new') {
            var url = location.origin + '/inv_item';
            location.replace(url);
        }
    });

    $("#customerid").on('change', function() {
        var item_id = $(this).val();
        if (item_id == 'new') {
            var url = '{{route("add_card_select_activity")}}';
            location.replace(url);
        }
    });

    $("#item_id").on('change', function() {
        var account_name = $('#item_id option:selected').attr('account_name');
        var account_id = $('#item_id option:selected').attr('account_id');
        var type = $('#item_id option:selected').data('item_type');
        var item_reg_name = $('#item_id option:selected').data('item_reg_name');
        var gst_id = $('#item_id option:selected').attr('gst_id');
        $("#item_reg_name").val(item_reg_name);
        if (type == 1) {
            $("#accountName").val(account_name);
            $("#chart_id").val(account_id);
            if (gst_id == 'GST' || gst_id == 'INP' || gst_id == 'CAP') {
                $("#is_tax").val('yes');
            } else {
                $("#is_tax").val('no');
            }
            $("#salesCode").hide();
            $("#accountName").attr('type', 'text');
        } else {
            $("#salesCode").show();
            $("#accountName").attr('type', 'hidden');
            $("#is_tax").val('');
        }
    });
    $("#salesCode").on('change', function() {
        var account_name = $('#salesCode option:selected').html();
        var chart_id = $(this).val();
        var gst_code = $('#salesCode option:selected').data('gst');
        $("#accountName").val(account_name);
        $("#chart_id").val(chart_id);
        if (gst_code == 'GST' || gst_code == 'INP' || gst_code == 'CAP') {
            $("#is_tax").val('yes');
        } else {
            $("#is_tax").val('no');
        }
    });

    $('#item_id').on('change', function() {
        var sell_price = $('#item_id option:selected').attr('data-sell-price');
        var quntity = $('#quantity').val();
        $('#rate').val(sell_price);
        var total = quntity * sell_price;
        $("#amount").val(total);
    });
});