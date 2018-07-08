<?php

namespace App\Classes;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TablesClass {

    public function getTables()
    {
        $tables = DB::select('SHOW TABLES');
        $data = [];
        foreach($tables as $table)
        {
            array_push($data,$table->Tables_in_tool_test);
        }
        return $data;
    }

    public function getColumns($table_name)
    {
        $columns = [];
        $columnsDescription = DB::select('describe '.$table_name);
        //$columns = DB::getSchemaBuilder()->getColumnListing($table_name);

        $i = 0;
        foreach ($columnsDescription as $column)
        {
            $columns[$i]["field"] =  $column->Field;
            $columns[$i]["type"] =  $column->Type;
            $i++;
        }

        return $columns;
    }

    public function getTableData($table_name)
    {
        return DB::table($table_name)->get()->toArray();
    }

    public function createTable($table_name, $table_columns)
    {
        if(!Schema::hasTable($table_name))
        {
            Schema::create($table_name, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('airline');
                $table->timestamps();
            });

            return true;
        }

        return false;
    }

    public function dropTable($table_name)
    {
        Schema::drop($table_name);
    }
}