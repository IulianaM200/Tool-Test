@extends('layouts.default')
@section('content')
    <div class="pos-f-t">
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-dark p-4">
                <h4 class="text-white">Database tables manipulation.</h4>
                <span class="text-muted">
                    Current update: add table in database.
                    <br>
                    Next update: insert data into selected table.
                </span>
            </div>
        </div>
        <nav class="navbar navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>

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
                    @if($table == "migrations" || $table == "password_resets" || $table == "users")
                    <button disabled class="btn btn-default" type="submit">Remove</button>
                    @else <button class="btn btn-default" type="submit">Remove</button>
                    @endif
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
        @include('database.addTable')
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-8">
            <h3>Operations</h3>
            <p>Insert, update, delete.</p>

            @include('database.insert')
        </div>
        <div class="col-sm-4">
            <h3>Query</h3>
            <p>Table manipulation using query.</p>

            @include('database.query')
        </div>
    </div>
@stop

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $(".input-group-append").click(function () {
            var table_name = $(this).parent().find('label').text();

            $.ajax({
                url: "{{route('tables/remove/table')}}",
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                    _table_name: table_name
                }
            }).done(function (response) {
                window.location = "{{route('tables')}}";
            });
        });

        $(".operation-insert").click(function () {
            alert('insert');
        });
    });

    //Check only one checkbox. Select only one table to manipulate.
    $(document).on('click', 'input[type="checkbox"]', function() {
        $('input[type="checkbox"]').not(this).prop('checked', false);
        var table_name = $(this).offsetParent().find('label').text();

        $.ajax({
            url: "{{route('tables/columns/data')}}",
            type: 'POST',
            data: {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                _data: table_name
            }
        }).done(function (response) {
            var data = JSON.parse(response);

            displayTable(data);

            displayInsertForm(data);

            //console.log("Selected table", data);
        });

        $("#selected_table_name").empty();

        $("#selected_table_name").append("<strong>" + table_name + "</strong>");
    });

    function displayTable(data) {
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
    }

    function displayInsertForm(data) {
        var append =
            "<label>Select operation:</label>" +
            "<select class='form-control'>" +
            "<option>INSERT</option>" +
            "<option>UPDATE</option>" +
            "<option>DELETE</option>" +
            "</select><br>";

        append += "<form action='{{route('tables/insert')}}' method='POST'>";
        append += "<input type='hidden' name='_token' value='{{ csrf_token() }}'>";
        append += "<input type='hidden' name='table_name' value='"+data['table_name']+"'>";

        for(var i in data['table_columns'])
        {
            var column = data['table_columns'][i]['field'];

            if(column !== "id" && column !== "created_at" && column !== "updated_at")
            {
                append += "<label>"+column+"</label>";
                append += "<input type='text' name='table_columns["+column+"]' placeholder='Enter "+column+"' class='form-control'>";
            }
        }
        append += "<button class='btn btn-default operation-insert'>Insert</button>";
        append += "</form>";

        $(".operations").empty();
        $(".operations").append(append);
    }
</script>