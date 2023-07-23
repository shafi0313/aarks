<script>
    $("input[type=number]").change(function() {
        const ato = parseFloat($(this).val());
        const id = $(this).data('id');
        const cls = $(this).data('total');
        const gl = $('#'+id+'_gl');
        const diff = $('#'+id+'_diff');
        const diff_inp = $('#'+id+'_diff_inp');
        const diff_output = (parseFloat(gl.val()) - ato).toFixed(2);
        diff.html(diff_output);
        diff_inp.val(diff_output);

        // Total Difference
        var total_ato = 0;
        $.each($("."+cls+"_ato"), function(i,v){
            if($(v).val()){
                total_ato += parseFloat($(v).val());
            }
        });
        console.log(cls, total_ato);
        $("."+cls).html(total_ato.toFixed(2));
        $("."+cls).val(total_ato.toFixed(2));

        $("."+cls+"_diff").html((parseFloat($("#"+cls+"_total").val()) - total_ato).toFixed(2));
        tax_g1_1a();
        tax_diff(cls);
    });
    function tax_g1_1a(){
        const tax_g1 = $(".tax_g1").html()??0;
        const tax_1a = $(".tax_1a").html()??0;
        $(".tax_1a_g1").html((parseFloat(tax_g1) - parseFloat(tax_1a)).toFixed(2));
        $(".1a_g1").val((parseFloat(tax_g1) - parseFloat(tax_1a)).toFixed(2));
    }
    function tax_diff(cls){
        const n1 = $(".tax_gl_"+cls).html()??0;
        const n2 = $(".tax_pl_"+cls).html()??0;
        const n3 = $(".tax_ato_"+cls).html()??0;
        let diff = $(".tax_diff_"+cls);

        let max = Math.max.apply(Math, [n1,n2,n3]);
        let min = Math.min.apply(Math, [n1,n3,n2]);
        console.log({'n1':n1, 'n2':n2, 'n3':n3, 'max':max, 'min':min, 'cls':cls});
        diff.html((parseFloat(max) - parseFloat(min)).toFixed(2));
    }
</script>
