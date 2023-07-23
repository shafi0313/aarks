<script>
    function incomeTax(type){
        $('td[id^="'+type+'"]').each(function(e) {
            let closestTdId = $(this).closest('td').attr('id');
            let i = getCell(closestTdId);
            let letter = i.number;
            let sales  = $("#sales-"+letter).find("input").val();
            let other  = $("#other-"+letter).find("input").val();
            let total  = parseFloat(sales) - parseFloat(other);
            if (!isNaN(total)) {
                let tr = $("tr[class^='tax']");
                tr.find('td[id^="'+letter+'"]').find("input").val(total.toFixed(2));
                tr.find('td[id^="'+letter+'"]').find("span").text(total.toFixed(2));
                // console.log(total);
            }
        });
    }
    $(function(){
        // all input value must be digist, integer,float only
        $('input').on('keyup', function(){
            var $this = $(this);
            var $val = $this.val();
            if($val){
                $this.val($val.replace(/[^0-9.]/g, ''));
            }
            // $('input[name="id[]"]').each(function(i,v) {
            //     console.log(i, $(this).val());
            // });
        });

        $(document).on('keyup', '.last_year_percent', function() {
            var last_year_percent = parseFloat($(this).val());
            var columnIndex       = parseInt(this.id.split('-')[1]);
            var balance           = parseFloat($('#last_year_amount-' + columnIndex).val());
            var type              = $(this).closest('tr').attr('class');
            if (isNaN(balance) || balance == 0) {
                toast('error', 'Please enter last year amount first');
                return false;
            }
            if (isNaN(last_year_percent) || last_year_percent == 0) {
                toast('error', 'Please enter last year percent first');
                return false;
            }
            $('.'+type).find('.month_percent-' + columnIndex).each(function() {
                $(this).val(last_year_percent.toFixed(2));
                let closestTdId = $(this).closest('td').attr('id');
                let i = getCell(closestTdId);
                let $amount = (last_year_percent * balance) / 100;
                $("#"+i.next_id).find('span').text(Math.abs($amount).toFixed(2));
                $("#"+i.next_id).find('input').val($amount.toFixed(2));

                if (type != 'tax') {
                    let sales_cell = getCell(i.next_id);
                    let total_type = 'sales';
                    if (type == 'with_sales'){
                        sumCell('sales', sales_cell);
                    } else {
                        sumCell('other', sales_cell);
                        total_type = 'other';
                    }
                    // incomeTax(total_type);
                    total_();
                }
            });
        });
        $(document).on('keyup', '.last_year_amount', function() {
            var last_amount = parseFloat($(this).val());
            var columnIndex = parseInt(this.id.split('-')[1]);
            var type        = $(this).closest('tr').attr('class');
            if (isNaN(last_amount) || last_amount == 0) {
                toast('error', 'Please enter last year percent first');
                return false;
            }
            var sum = 0;
            $('.'+type).find('.last_year_amount').each(function() {
                let closestTdId = $(this).closest('td').attr('id');
                let i = getCell(closestTdId);
                let val = parseFloat($(this).val());
                if (!isNaN(val)) {
                    sum += val;
                }
            });

            let total_type = 'sales';
            if (type == 'with_sales'){
                $("#sales-C").find('input').val(sum.toFixed(2));
                $("#sales-C").find('b').text(Math.abs(sum).toFixed(2));
            } else {
                $("#other-C").find('input').val(sum.toFixed(2));
                $("#other-C").find('b').text(Math.abs(sum).toFixed(2));
                total_type = 'other';
            }
            incomeTax(total_type);
        });
        $('tr').each(function(e){
            let $input = $(this).find('input');
            let type   = $(this).attr('class');
            let cell_type = type == 'with_sales'?'sales':'other';
            $input.on('keyup', function(){
                let $this = $(this);
                let closestTdId = $(this).closest('td').attr('id');
                let i = getCell(closestTdId);
                // console.table(i);
                let balance = parseFloat($("#C-"+i.number).find('input').val());
                if($this.hasClass('with_sales_percent')){
                    let $percent = $this.val();
                    let $amount = ($percent * balance) / 100;
                    $("#"+i.next_id).find('span').text(Math.abs($amount).toFixed(2));
                    $("#"+i.next_id).find('input').val($amount.toFixed(2));

                    let sales_cell = getCell(i.next_id);
                    sumCell(cell_type, sales_cell);
                    total_();
                }
                $('td[id^="'+i.letter+'"]').find('input').prop('disabled', false);
                getBudgetPl();
                // incomeTax(cell_type);
            });
        });

        $(".month_percent").on('keyup', function(){
            let $this = $(this);
            let closestTdId = $(this).closest('td').attr('id');
            let i = getCell(closestTdId);
            let balance = parseFloat($("#C-"+i.number).find('input').val());
            if($this.hasClass('month_percent-'+i.number)){
                let $percent = $this.val();
                let $amount = ($percent * balance) / 100;
                $("#"+i.next_id).find('span').text(Math.abs($amount).toFixed(2));
                $("#"+i.next_id).find('input').val($amount.toFixed(2));
                let sales_cell = getCell(i.next_id);
            }
        });

        // Handle form submit event
        $("#business-plan-form-").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{route('business-plan.store')}}', // URL to submit form data
                type: "POST",
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    // Handle successful response
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        });
    });
    function getBudgetPl(){
        let exp = $(".budget_amount_2");
        let inc = parseFloat($(".total_budget").val());
        let total_exp = 0;
        $.each(exp, function(i, v){
            total_exp += parseFloat($(v).val());

        });
        let profit_loss =  inc - total_exp;
        $(".budget_pl").text(profit_loss.toFixed(2));
    }
    function sumCell(type, cell){
        let sum = 0;
        $('td[id^="'+cell.letter+'"]').each(function() {
            if($(this).hasClass(type)){
                // console.log($(this).html(),type);
                let val = parseFloat($(this).find('input').val());
                if (!isNaN(val)) {
                    sum += val;
                }
            }
        });
        $("#"+type+"-"+cell.letter).find('input').val(sum.toFixed(2));
        $("#"+type+"-"+cell.letter).find('b').text(Math.abs(sum).toFixed(2));
    }
    function getCell(id) {
        const parts = id.split('-');
        const letterCode = parts[0].charCodeAt(0);

        const prevId = (parts[0].length === 1 && letterCode === 65) ? null : (parts[0].length === 1) ? String.fromCharCode(letterCode - 1) + "-" + parts[1] : parts[0].slice(0, -1) + String.fromCharCode(parts[0].slice(-1).charCodeAt(0) - 1) + "-" + parts[1];
        const nextId = (parts[0].length === 1 && letterCode === 90) ? null : (parts[0].length === 1) ? String.fromCharCode(letterCode + 1) + '-' + parts[1] : parts[0].slice(0, -1) + String.fromCharCode(parts[0].slice(-1).charCodeAt(0) + 1) + "-" + parts[1];

        return {
            letter: parts[0],
            number: parts[1],
            current_id: id,
            prev_id: prevId,
            next_id: nextId
        };
    }
    function total_(){
        $('.total_').each(function() {
            var columnIndex = parseInt(this.id.split('-')[1]);
            var sum = 0;
            $('._amount-' + columnIndex).each(function() {
                var value = parseFloat($(this).val());
                if (!isNaN(value)) {
                sum += value;
                }
            });
            $(this).find('b').text(Math.abs(sum).toFixed(2));
            $(this).find('input').val(sum.toFixed(2));
        });
    }

</script>
