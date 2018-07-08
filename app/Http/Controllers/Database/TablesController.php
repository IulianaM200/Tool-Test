<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Classes\TablesClass;

class TablesController extends Controller
{
    protected $tables;

    protected $message;

    public function __construct()
    {
        $this->tables = new TablesClass();
    }

    public function tables()
    {
        return View::make('database.tables', ['tables' => $this->tables->getTables()]);
    }

    public function tablesColumnsAndData(Request $request)
    {
        $table_name = $request->post('_data');

        $table_columns = $this->tables->getColumns($table_name);

        $table_data = $this->tables->getTableData($table_name);

        $data = compact('table_name','table_columns','table_data');

        return json_encode($data);
    }

    public function addTable(Request $request)
    {
        $table_name = $request->post('_table_name');

        $table_columns = $request->post('_table_columns');

        if($table_name != null & $table_columns != null)
        {
            $new_table = $this->tables->createTable($table_name, $table_columns);

            if($new_table == false) $message = "Table name is used!";
            else $message = "Table successfully created";
        }

        $data = compact('table_name','table_columns', 'message');

        return json_encode($data);
    }

    public function removeTable(Request $request)
    {
        $table_name = $request->post('_table_name');
        if($table_name != null)
            $this->tables->dropTable($table_name);

        return json_encode(['message'=> 'Remove table']);
    }

}
