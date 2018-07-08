<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Classes\TablesClass;

class TablesController extends Controller
{
    protected $tables;

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
}
