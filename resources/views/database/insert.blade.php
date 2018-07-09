<div class="operations">
</div>

<script>
    $(document).on('click', 'input[type="checkbox"]', function() {
        $('input[type="checkbox"]').not(this).prop('checked', false);
        var table_name = $(this).offsetParent().find('label').text();

        console.log("Table name", table_name);
    });
</script>