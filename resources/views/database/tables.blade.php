@extends('layouts.default')
@section('content')
    <h1>Tables from <strong>{{env('DB_DATABASE')}}</strong> database</h1>

    @if(isset($tables))
        @foreach($tables as $table)
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox">
                    </div>
                </div>

                <label class="form-control">{{ $table }}</label>

                <div class="input-group-append">
                    <button class="btn btn-default" type="submit">Remove</button>
                </div>
            </div>
        @endforeach
    @endif

    <hr>

    <div class="row">
        <div class="container">
            <h3>View select table</h3>
            <p id="selected_table_name">No table selected.</p>
            <div id="selected_table_data"></div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4">
            <h3>Operations</h3>
            <p>Create, update,delete.</p>
        </div>
        <div class="col-sm-4">
            <h3>Query</h3>
            <p>Table manipulation using query.</p>
        </div>
    </div>
@stop

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    //Check only one checkbox. Select only one table to manipulate.
    $(document).on('click', 'input[type="checkbox"]', function() {
        $('input[type="checkbox"]').not(this).prop('checked', false);

        var table_name = $(this).offsetParent().find('label').text();

        $.ajax({
            url: "{{route('tables/columns/data')}}",
            type: 'POST',
            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            data: {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                _data: table_name
            }
        }).done(function (response) {
            var data = JSON.parse(response);

            var table_head = "";
            var table_body = "";
            if(data['table_columns'] !== null)
            {
                table_head += "<thead class='thead-dark'><tr>";
                for (var i in data['table_columns'] )
                {
                    table_head += "<th scope='col'>" + data['table_columns'][i]['field'] + "</th>";
                }
                table_head += "</tr></thead>";
            }

            if(data['table_data'].length !== 0)
            {
                table_body += "<tbody>";
                for (var i in data['table_data'] )
                {
                    table_body += "<tr>";
                    for(var value in data['table_data'][i])
                    {
                        table_body += "<td>" + data['table_data'][i][value] + "</td>";
                    }
                    table_body += "</tr>";
                }
                table_body += "</tbody>";
            }
            if(data['table_data'].length === 0){
                table_body += "<tbody>";
                table_body += "<tr><td colspan='"+data['table_columns'].length+"'>NO DATA</td></tr>";
                table_body += "</tbody>";
            }

            var table = '<table class="table">' + table_head + table_body + '</table>';

            $('#selected_table_data').empty();
            $('#selected_table_data').append(table);
        });

        $("#selected_table_name").empty();

        $("#selected_table_name").append("<strong>" + table_name + "</strong>");
    });
</script>