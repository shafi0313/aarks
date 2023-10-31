<script>
    $(function(){
        $('[id^="total_income-"]').each(function() {
            const id = $(this).attr('id');
            const split = id.split('-');
            const type = split[0];
            const number = split[1];
            const income = parseFloat($(this).val() ?? 0);
            const expense = parseFloat($('#total_expense-' + number).val() ?? 0);
            // console.log(income, expense);

            // Calculate EBIT
            const ebit = parseFloat(income) - parseFloat(expense);
            $("#total_ebit-" + number).html(ebit.toFixed(2));

            const tax    = parseFloat($('#total_tax-' + number).text() ?? 0);
            const profit = parseFloat(ebit) - parseFloat(tax);
            $("#total_profit-" + number).html(profit.toFixed(2));

            const margin = parseFloat(ebit) * 100 / parseFloat(income);
            $("#total_margin-" + number).html(margin.toFixed(2) + '%');
        });
    });
    function printDiv() {
        let divToPrint = $("#print-area");
        newWin = window.open("");
        newWin.document.write(
            '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">'
            );
        newWin.document.write(`<style>
            body {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }
            table,tr,td,th,b {
                border-collapse: collapse;
                font-size: 8px;
                padding: 0px !important;
                border: 1px solid #eee;
            }
            img{
                width:85px;
            }
            .text-right{
                text-align: right;
            }
            .text-center{
                text-align: center;
            }
            .text-left{
                text-align: left;
            }

            @media print {
                @page {
                    size: A4 landscape;
                }

                .page-break {
                    page-break-before: always;
                }
            }
            </style>`);
        newWin.document.write(divToPrint.html());
        newWin.document.close();
        newWin.focus();
        newWin.print();
    }
</script>
