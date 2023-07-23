<script>
    $(function(){
        $('input').on('keyup', function(){
            var $this = $(this);
            var $val = $this.val();
            if($val){
                $this.val($val.replace(/[^0-9.]/g, ''));
            }
        });
        $('.with_sales').each(function(){
            var $this = $(this);
            var $input = $this.find('input');
            var $last_year_amount = $this.find('.last_year_with_sales').val();
            $input.on('keyup', function(){
                var $this = $(this);
                if($this.hasClass('with_sales_percent')){
                    var $percent = $this.val();
                    var $amount = ($percent * $last_year_amount) / 100;
                    $this.closest('tr').find('td').eq(5).find('.budget_amount').text(Math.abs($amount).toFixed(2)).val($amount.toFixed(2));
                    var $total = 0;
                    $.each($('.with_sales').find('input.budget_amount '), function(i, v){
                        $total += parseFloat($(v).val());
                    });
                    $('.total_budget').text(Math.abs($total).toFixed(2)).val($total.toFixed(2));
                }
                $(".without_sales").find("input").prop('disabled', false);
                getBudgetPl();
            });
        });
        $('.without_sales').each(function(){
            var $this = $(this);
            var $input = $this.find('input.percent');
            var $last_year_amount = $this.find('.last_year_amount').val();

            $input.on('keyup', function(){
                var $this = $(this);
                if(!$this.hasClass('with_sales_percent')){
                    var $percent = $this.val();
                    var $total_budget = parseFloat($('.total_budget').val());
                    var $amount = ($percent * $total_budget) / 100;
                    $this.closest('tr').find('td').eq(5).find('.budget_amount').text(Math.abs($amount).toFixed(2)).val($amount.toFixed(2));
                    var $total = 0;
                    $.each($('.without_sales').find('input.budget_amount '), function(i, v){
                        $total += parseFloat($(v).val());
                    });
                    $('.without_sales_total').find('.total_budget').text(Math.abs($total).toFixed(2)).val($total.toFixed(2));
                    getBudgetPl();
                }
            });
        });
    });
    function getBudgetPl(){
        let exp = $(".budget_amount_2");
        let inc = parseFloat($(".total_budget").val());
        let total_exp = 0;
        $.each(exp, function(i, v){
            console.log($(v).val());
            total_exp += parseFloat($(v).val());

        });
        let profit_loss =  inc - total_exp;
        $(".budget_pl").text(profit_loss.toFixed(2));
    }
</script>
