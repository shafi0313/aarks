
<!-- inline scripts related to this page -->
<script>
    $(document).ready(function() {
        // $('#example').DataTable( {
        //     "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
        //     "order": [[ 0, "asc" ]]
        // });
        readData();
        jobReadData();
    });

//COPY FTOM ONLINE
$('.add-item').on('click', function() {
    var job_title       = $('#job_title').val();
    var job_description = $('#job_des').val();
    var price           = $('#price').val();
    var disc            = $('#disc_rate').val()==''?0:$('#disc_rate').val();
    var freight         = $('#freight_charge').val()==''?0:$('#freight_charge').val();
    var account         = $('#ac_code_name').val();
    var chart_id        = $('#chart_id').val();
    var tax             = $('#is_tax').val();

    if (job_title == '') {
        toast('warning', 'Please enter job title');
        $('#job_title').focus();
        return false;
    }
    if (job_description == '') {
        toast('warning', 'Please enter job description');
        $('#job_des').focus();
        return false;
    }
    if (price == '') {
        toast('warning', 'Please enter price');
        $('#price').focus();
        return false;
    }
    if (account == '') {
        toast('warning', 'Income Account Tax');
        $('#ac_code_name').focus();
        return false;
    }
    if (tax == '') {
        toast('warning', 'Income Tax');
        $('#is_tax').focus();
        return false;
    }
    var totalamount = gst_total = price;
    var gst = tax_rate = disc_amount = 0;
    if (disc != '') {
        disc_amount = totalamount * (disc/100);
        totalamount = gst_total = (totalamount - (totalamount * (disc/100)));
    }
    if (freight != '') {
        totalamount = gst_total = parseFloat(freight) + parseFloat(totalamount);
    }
    if (tax == 'yes') {
        totalamount = parseFloat(totalamount) + (totalamount * 0.1);
        gst = gst_total * 0.1;
        tax_rate = '10.00';
    }else{
        tax_rate = '0.00'
    }
    var pro_name = $('#ac_code_name').val();
    var html = '<tr>';
    html += '<tr class="trData"><td class="serial"></td><td>' + job_description + '</td><td>' + parseFloat(price).toFixed(2) + '</td><td class="text-right">' + parseFloat(disc).toFixed(2) + '</td><td class="text-right">' + parseFloat(freight).toFixed(2) + '</td><td class="text-right">' + pro_name + '</td><td class="text-right">'+tax_rate+'</td><td class="text-right">' + parseFloat(totalamount).toFixed(2) + '</td><td align="center">';
    html += '<input type="hidden" name="job_title[]" value="' + job_title + '" />';
    html += '<input type="hidden" name="job_des[]" value="' + job_description + '" />';
    html += '<input type="hidden" name="price[]" value="' + price + '" />';
    html += '<input type="hidden" name="disc_rate[]" value="' + disc + '" />';
    html += '<input type="hidden" name="disc_amount[]" value="' + disc_amount + '" />';
    html += '<input type="hidden" name="freight_charge[]" value="' + freight + '" />';
    html += '<input type="hidden" name="tax_rate[]" value="' + tax_rate + '" />';
    html += '<input type="hidden" name="is_tax[]" value="' + tax + '" />';
    if (tax == 'yes') {
    html += '<input type="hidden" name="gst_amt[]" value="' + gst + '" />';
    }else{
    html += '<input type="hidden" name="gst_amt[]" value="0" />';
    }
    html += '<input type="hidden" name="totalamount[]" value="' + totalamount + '" />';
    html += '<input type="hidden" name="chart_id[]" value="' + chart_id + '" />';
    html += '<a class="item-delete" href="#"><i class="fas fa-trash"></i></a></td></tr>';
    toast('success','Added');
    $('.item-table tbody').append(html);
    $('#job_title').val('');
    $('#job_des').val('');
    $('#price').val('');
    $('#disc_rate').val('');
    $('#freight_charge').val('');
    serialMaintain();
});

$('.item-table').on('click', '.item-delete', function(e) {
    var element = $(this).parents('tr');
    element.remove();
    toast('warnig','item removed!');
    e.preventDefault();
    serialMaintain();
});

function serialMaintain() {
    var i = 1;
    var subtotal = gst_amt_subtotal=0;
    $('.serial').each(function(key, element) {
        $(element).html(i);
        var total = $(element).parents('tr').find('input[name="totalamount[]"]').val();
        var gst_amt = $(element).parents('tr').find('input[name="gst_amt[]"]').val();
        subtotal += + parseFloat(total);
        gst_amt_subtotal += + parseFloat(gst_amt);
        i++;
    });
    $('.sub-total').html(subtotal.toFixed(2));
    $('#total_amount').val(subtotal);
    $('#gst_amt_subtotal').val(gst_amt_subtotal);
};

function bankamount(){
    $("#payment_amount").removeAttr('disabled','disabled')
}
$("#recurringStore").on('submit',function(e){
    e.preventDefault();
    let data = $(this).serialize();
    let url = $(this).attr('action');
    let method = $(this).attr('method');
    // $('input[type=submit]', '#recurringStore').prop('disabled', 'disabled');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success:res=>{
            if(res.status == 200){
                $(".trData").remove();
                $(".sub-total").html('$ 0.00')
                $("#payment_amount").val('')
                $("#inv_no").val(res.inv_no.toString().padStart(8, '0'));
                toast('success',res.message);
                // $('input[type=submit]', '#recurringStore').prop('disabled', false);
            }else{
                toast('error',res.message);
            }
        },
        error:err=>{
            if(err.status == 500){
                toast('error',err.responseText);
            }
            $.each(err.responseJSON.errors, (i,v)=>{
                toast('error', v);
            })
        }
    });
});

$('#recurringStore input').on('change', (e)=> {
    var radioval = $('input[name=recur_end]:checked', '#recurringStore').val();
    if(radioval == 'radio1'){
        $('#untill_date').removeAttr('disabled', 'disabled');
        $('#recur_tran').attr('disabled', 'disabled');
        $('#unlimited').attr('disabled', 'disabled');
    }else if(radioval == 'radio2'){
        $('#unlimited').removeAttr('disabled', 'disabled');
        $('#recur_tran').attr('disabled', 'disabled');
        $('#untill_date').attr('disabled', 'disabled');
    }else {
        $('#recur_tran').removeAttr('disabled', 'disabled');
        $('#unlimited').attr('disabled', 'disabled');
        $('#untill_date').attr('disabled', 'disabled');
    }
});
$('.item_date').datepicker({
    format        : 'dd/mm/yyyy',
    startDate     : '{{now()->format("d/m/Y")}}',
    autoclose     : "close",
    todayHighlight: true,
    clearBtn      : true,
});
</script>
