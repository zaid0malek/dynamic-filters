<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\DataTables;

class CommitteeController extends Controller
{
    public function index(){

        $t=time();
        $response = Http::get("https://api.open.fec.gov/v1/committees", ["api_key" => env('FEC_API_KEY'), "per_page" => 100]);
        $response = collect(json_decode($response->body()))->get("pagination");
        $pages=$response->pages;
        set_time_limit(6000);
        Committee::truncate();
        for ($current_page = 1; $current_page <= 100; $current_page ++) {
            $response = Http::get("https://api.open.fec.gov/v1/committees", ["api_key" => env('FEC_API_KEY'), "per_page" => 100, "page" => $current_page]);
            $response = collect(json_decode($response->body()))->get("results");
            dump($current_page,time()-$t);
            foreach($response as $committee_data) {
                $committee = new Committee();
                $committee->state = $committee_data->state;
                $committee->designation_full = $committee_data->designation_full;
                $committee->name = $committee_data->name;
                $committee->last_file_date = $committee_data->last_file_date;
                $committee->first_f1_date = $committee_data->first_f1_date;
                $committee->organization_type_full = $committee_data->organization_type_full;
                $committee->sponsor_candidate_list = json_encode($committee_data->sponsor_candidate_list);
                $committee->party = $committee_data->party;
                $committee->party_full = $committee_data->party_full;
                $committee->designation = $committee_data->designation;
                $committee->organization_type = $committee_data->organization_type;
                $committee->affiliated_committee_name = $committee_data->affiliated_committee_name;
                $committee->committee_type_full = $committee_data->committee_type_full;
                $committee->first_file_date = $committee_data->first_file_date;
                $committee->committee_type = $committee_data->committee_type;
                $committee->treasurer_name = $committee_data->treasurer_name;
                $committee->filing_frequency = $committee_data->filing_frequency;
                $committee->committee_id = $committee_data->committee_id;
                $committee->sponsor_candidate_ids = json_encode($committee_data->sponsor_candidate_ids);
                $committee->candidate_ids = json_encode($committee_data->candidate_ids);
                $committee->last_f1_date = $committee_data->last_f1_date;
                $committee->cycles = json_encode($committee_data->cycles);
                $committee->page = $current_page;
                $committee->save();
            }
        }
     
        dd("Success");
    }

    public function showData()
    {
        $data = Committee::paginate(10);
        // foreach($data as $d){
        //     dump($d);
        // }     
        return view("show-committees")->with(["committees" => $data]);
    }
    public function showData2(Request $request)
    {
        if ($request->ajax()) {
            $data = Committee::get();
            return DataTables::of($data)->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('show-committees2');
    }
    
}
