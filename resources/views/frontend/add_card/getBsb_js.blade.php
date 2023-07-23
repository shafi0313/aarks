<script>
function getBsb(val){
    let bsb_container = $("#bsb-container")
    let url = '{{route("bsbtable.index")}}';
    if(val == 'Bank Transfer'){
        bsb_container.slideDown().show();
        $.ajax({
            url:url,
            method:'get',
            success:res=>{
                let data = '';
                if(res.status == 200){
                    $.each(res.bsbs, function(i,v){
                        data += '<tr><td class="text-center"><input style="height: 20px;width: 20px;" type="radio" value="'+v.id+'" name="bsb_table_id"></td><td> '+v.bsb_number+' </td><td> '+v.account_number+' </td></tr>';
                    });
                    $("#bsb-content").html(data);
                }
                console.log(res);
            }
        });
    } else {
        bsb_container.slideUp().hide();
    }
    }
</script>
