<script>
    // $(document).ready(function() {
    //     $('#example').DataTable({
    //         "order": []
    //     });
    // });

    $('#example').DataTable({
        "lengthMenu": [
            [50, 100, -1],
            [50, 100, "All"]
        ],
        "order": [
            [0, "asc"]
        ]
    });
</script>
