<div class="col-sm-4">
    <h3>Create table</h3>
    <p>Add new table in your database.</p>

    <div class="form-group">
        <label for="add_table_name">Name</label>
        <input id="add_table_name" type="text" class="form-control" id="add_table_name" aria-describedby="add_table_name_help" placeholder="Enter table name">
        <small id="add_table_name_help" class="form-text text-muted">Enter table name. No space allowed. No special characters. No numbers. Ex: friends_test, companies, job_details...</small>
    </div>
</div>

<div class="col-sm-3">
    <h3>Select number of columns</h3>
    <p>Be aware that first column is the primary key of the table.</p>

    <div class="form-group table-column-number">
        <label for="add_table_column_number">Number of columns</label>
        <select multiple class="form-control" id="add_table_column_number">
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
        </select>
    </div>
</div>

<div class="col-sm-5">
    <h3>Columns configuration</h3>

    <div id="table_columns_process">
    </div>

    <hr>

    <button type="submit" class="btn btn-default insert-table">Insert table into database</button>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>

    $('#add_table_column_number').click(function () {
        var table_column_number = $(this).val();

        var table = "<table>" + "<tr><th>Name</th><th>Type</th><th>Size</th></tr>";

        for(var i = 0; i < table_column_number; i++)
        {
            if(i === 0)
            {
                table +=
                    "<tr>" +
                    "<td><input disabled type='text' value='id' class='form-control table-column-name_"+i+"'></td>" +

                    "<td><select disabled class='form-control table-column-type_"+i+"'>" +
                    "<option selected>INT</option>" +
                    "</select></td>" +

                    "<td><select disabled class='form-control table-column-size_"+i+"'>" +
                    "<option selected>500</option>" +
                    "</select></td>" +
                    "</tr>";
            }
            else
            {
                table +=
                    "<tr>" +
                    "<td><input type='text' placeholder='Enter column name' class='form-control table-column-name_"+i+"'></td>" +

                    "<td><select class='form-control table-column-type_"+i+"'>" +
                    "<option>VARCHAR</option>" +
                    "<option>TEXT</option>" +
                    "<option>INT</option>" +
                    "</select></td>" +

                    "<td><select class='form-control table-column-size_"+i+"'>" +
                    "<option>10</option>" +
                    "<option>250</option>" +
                    "<option>500</option>" +
                    "</select></td>" +
                    "</tr>";
            }
        }
        table +=  "</table>";


        $('#table_columns_process').empty();
        $('#table_columns_process').append(table);
    });

    $('.insert-table').click(function (e) {
        var table_name = $('#add_table_name').val();
        var table_column_number = $('.table-column-number').find(":selected").text();

        var columns = [];
        for(var i = 0; i < table_column_number; i++)
        {
            columns.push(
                {
                    'name':$(".table-column-name_"+i).val(),
                    'type':$(".table-column-type_"+i).val(),
                    'size':$(".table-column-size_"+i).val()
                }
            );
        }

        $.ajax({
            url: "{{route('tables/add/table')}}",
            type: 'POST',
            data: {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                _table_name: table_name,
                _table_columns: columns
            }
        }).done(function (response) {
            //var data = JSON.parse(response);
            //console.log(data);
            window.location = "{{route('tables')}}";
        });

        //console.log(table_name, columns);
    });

</script>