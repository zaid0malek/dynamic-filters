<?php

namespace App\Http\Controllers;

use App\Models\Bankdataset;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BankdatasetController extends Controller
{
    public function showData(Request $request)
    {
        if ($request->ajax()) {
            $data = Bankdataset::get();
            return DataTables::of($data)->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('show-bankdata');
    }

    public function filter(Request $request)
    {
        $data = Bankdataset::select('*');
        // $alreadyapplied = array();
        // $column_names = [
        //     'Date' => 'date',
        //     'Domain' => 'domain',
        //     'Location' => 'location',
        //     'Transaction Count' => 'transaction_count',
        //     'Value' => 'value',
        // ];
        $allfilters = json_decode($request->input('allfilters'), true);

        if (!empty($allfilters)) {
            foreach ($allfilters as $filter) {
                $filterParts = explode(' : ', $filter);

                $filterName = ucfirst(strtolower(str_replace(' ', '_', trim($filterParts[0]))));
                $filterOperator = trim($filterParts[1]);
                $filterValue = trim($filterParts[2]);
                $value = "%%";
                switch ($filterOperator) {
                    case 'Starts With':
                        $value = $filterValue . "%";
                        $op = 'LIKE';
                        break;
                    case 'Contains':
                        $value = "%" . $filterValue . "%";
                        $op = 'LIKE';
                        break;
                    case 'Ends With':
                        $value = "%" . $filterValue;
                        $op = 'LIKE';
                        break;
                    case 'Is':
                        $value = $filterValue;
                        $op = 'LIKE';
                        break;
                    case 'Less Than':
                        $value = $filterValue;
                        $op = '<';
                        break;
                    case 'Greater Than':
                        $value = $filterValue;
                        $op = '>';
                        break;
                }
                if (!isset($columnConditions[$filterName])) {
                    $columnConditions[$filterName] = [];
                }

                $columnConditions[$filterName][] = [
                    'operator' => $op,
                    'value' => $value,
                ];
            }
            // dd($columnConditions);
            $data->where(function ($query) use ($columnConditions) {
                foreach ($columnConditions as $column => $conditions) {
                    $query->where(function ($subquery) use ($conditions, $column) {
                        foreach ($conditions as $condition) {
                            $subquery->orWhere($column, $condition['operator'], $condition['value']);
                        }
                    });
                }
            });
        }
        // $data->ddRawSql();
        return DataTables::of($data)->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);


    }
}