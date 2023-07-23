<!-- inline scripts related to this page -->
<style>
    img {
        width: 120px;
    }
    @media print {

        body *,
        #main * {
            display: none;
        }

        #main,
        #main #printarea,
        #main #printarea * {
            display: block;
        }

        img {
            width: 60px;
        }
    }

</style>

<script>
    function printDiv(divId) {
        var divToPrint = document.getElementById(divId);
        newWin = window.open();
        newWin.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">');
        newWin.document.write(`<style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        table,tr,td,th {
            border-collapse: collapse;
        }
        .table-code tr, td, th {
            text-align: left;padding: 5px;
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
        }</style>`);
        newWin.document.write(divToPrint.innerHTML);
        newWin.document.close();
        newWin.focus();
        newWin.print();
    }
</script>
