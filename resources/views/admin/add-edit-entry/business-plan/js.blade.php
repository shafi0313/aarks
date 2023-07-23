<script>
    $(function(){
        // all input value must be digist, integer,float only
        $('input').on('keyup', function(){
            var $this = $(this);
            var $val = $this.val();
            if($val){
                $this.val($val.replace(/[^0-9.]/g, ''));
            }
            // taxCalculation(this);
        });
        $('.with_sales').each(function(e){
            let $input = $(this).find('input');
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
                    sumCell('sales', sales_cell);
                    total_();
                }
                $('td[id^="'+i.letter+'"]').find('input').prop('readonly', false);
                getBudgetPl();
            });
        });
        $('.without_sales').each(function(){
            let $input = $(this).find('input.percent');
            $input.on('keyup', function(){
                let $this = $(this);

                let closestTdId = $(this).closest('td').attr('id');
                let i = getCell(closestTdId);
                // console.table(i);

                if($this.hasClass('percent')){
                    let $percent = $this.val();

                    let next_cell = getCell(i.next_id);
                    let cell_total = parseFloat($("#sales_"+next_cell.letter).find('input').val());

                    let $amount = ($percent * cell_total) / 100;
                    $("#"+i.next_id).find('span').text(Math.abs($amount).toFixed(2));
                    $("#"+i.next_id).find('input').val($amount.toFixed(2));
                    sumCell('other', next_cell);
                    total_();
                }
                getBudgetPl();
            });
        });

        $(document).on('keyup', '.last_year_percent', function(){
            let $this = $(this);
            let closestTdId = $(this).closest('td').attr('id');
            let i = getCell(closestTdId);
            var percent = parseFloat($(this).val());
            let balance = parseFloat($("#C-"+i.number).find('input').val());
            if (isNaN(balance) || balance == 0) {
                toast('error', 'Please enter last year amount first');
                return false;
            }
            if (isNaN(percent) || percent == 0) {
                toast('error', 'Please enter last year percent first');
                return false;
            }
            $('.tax').find('.month_percent-' + i.number).each(function() {
                $(this).val(percent.toFixed(2));
                let closestTdId = $(this).closest('td').attr('id');
                let i = getCell(closestTdId);
                let $amount = (percent * balance) / 100;
                $("#"+i.next_id).find('span').text(Math.abs($amount).toFixed(2));
                $("#"+i.next_id).find('input').val($amount.toFixed(2));
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
            let val = parseFloat($(this).find('input').val());
            if (!isNaN(val)) {
                sum += val;
            }
            }
        });
        console.log(sum);
        $("#"+type+"_"+cell.letter).find('input').val(sum.toFixed(2));
        $("#"+type+"_"+cell.letter).find('b').text(Math.abs(sum).toFixed(2));
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
