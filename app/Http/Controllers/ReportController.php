<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;

use App\Models\Report;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\Room;
use App\Models\ServiceProvider;
use Carbon\Carbon;

use File;

class ReportController extends Controller
{
    public function index(Request $request){
        
        $order_list = Order::whereHas('report', function ($query) {
            $query->whereIn('status', [3,4]);
        })->with('report')->with('shop');

        if($request->service_provider){
            $service_provider_id = $request->service_provider;
            $order_list = $order_list->whereHas('serviceProviders', function($query) use ($service_provider_id ){
                $query->where('id', $service_provider_id);
            });
        }
        if($request->id){
            $order_list = $order_list->where('id', $request->id);
        }
        if($request->name){
            $order_list = $order_list->where('name', $request->name);
        }
        if($request->phone){
            $order_list = $order_list->where('phone', $request->phone);
        }
        if($request->start_time){
            $order_list = $order_list->where('start_time', ">=", $request->start_time);
        }
        if($request->end_time){
            $order_list = $order_list->where('end_time', "<=", $request->end_time);
        }
        if($request->service){
            $order_list = $order_list->where('service_id', $request->service);
        }
        if($request->shop){
            $order_list = $order_list->where('shop_id', $request->shop);
        }
        $order_list = $order_list->paginate(10);

        if($request->service_provider){
            $view_data['q2'] = ['非常滿意' => 0, '滿意' => 0, '普通' => 0, '不滿意' => 0];
            $view_data['q3'] = ['非常滿意' => 0, '滿意' => 0, '普通' => 0, '不滿意' => 0];
            $view_data['q4'] = ['非常滿意' => 0, '滿意' => 0, '普通' => 0, '不滿意' => 0];
            foreach ($order_list as $key => $order) {
                $view_data['q2'][$order->report->q2]++;
                $view_data['q3'][$order->report->q3]++;
                $view_data['q4'][$order->report->q4]++;
            }
        }

        $view_data['order_list'] = $order_list;

        $service_provider_list = ServiceProvider::with('shop')->get();
        foreach ($service_provider_list as $key => $service_provider) {
            $service_provider_name = $service_provider->name."(".$service_provider->shop->name.")";
            $view_data['service_provider_list'][] = ["id" => $service_provider->id, "name" => $service_provider_name];
        }
        $view_data['request'] = $request;
        $view_data['service_list'] = Service::all();
        $view_data['shop_list'] = Shop::all();
        return view('admin.report.index', $view_data);
    }

    private function insertValidation(Request $request){
        $v = Validator::make($request->all(), [
            'jwt' => 'required|max:255',
            'q0' => 'required|max:255',
            'q1' => 'required|max:255',
            'q2' => 'required|max:255',
            'q3' => 'required|max:255',
            'q4' => 'required|max:255',
            'q5' => 'required|max:255',
            'q6' => 'required|max:255',
            // 'q7' => 'required|max:255',
        ]);
    
        if ($v->fails())
        {
            return -1;
        }
        else
            return 1;
    }

    
    public function FinishedService(){
       $readyForQuiz = Order::where('status',5)->doesntHave("report")->where('phone', '!=', '現場客')->whereDate('end_time','>=', "2018-06-22 00:00:00")->where('end_time','<',Carbon::now())->get();
        foreach($readyForQuiz as $mdata){
            $report = new Report;
            $report->order_id = $mdata->id;
            $report->status = 0;
            $report->save();
        }
    }

    public function sendReport(Request $request){
            
        $id = base64_decode($request->jwt);
        $query = Report::where('order_id', $id)->where('status','2')->first();

        $is_order = Order::where('id',$id)->first();
        if( !$query || $this->insertValidation($request) == -1 || !$is_order){
            return response()->json([
                "res"=>-1,
                "validation"=>$this->insertValidation($request),
                "is_order"=>$is_order,
                "query"=>$query,
                "data"=>$request->all()
                ]);
        }
        else{
            $report = Report::where('order_id', $id)->update(
                ["q0" => $request->q0,
                "q1" => $request->q1,
                "q2" => $request->q2,
                "q3" => $request->q3,
                "q4" => $request->q4,
                "q5" => $request->q5,
                "q6" => $request->q6,
                "q7" => $request->q7,
                "q1-reason" => $request->q1_reason,
                "q2-reason" => $request->q2_reason,
                "q3-reason" => $request->q3_reason,
                "q4-reason" => $request->q4_reason,
                "q6-reason" => $request->q6_reason,
                "status" => '3']
            );

            return response()->json(["res"=>1]);
        }

        
    }


    public function getQuiz(Request $request){
        try{
            if($request->jwt){
                $id = base64_decode( $request->jwt);
                $status = Report::where('order_id',$id)->get();
                if($status[0]->status == 2 ){
                    $service_providers = ServiceProvider::whereHas('orders', function($query) use ($id) {
                        $query->where('id', $id);
                    })->with('shop')->get();
                    $service_provider_information = "";
                    foreach ($service_providers as $key => $service_provider) {
                        $service_provider_information .= $service_provider->name."(".$service_provider->shop->name.")";
                    }
                    $service_provider_information .= "";
                    $view_data["service_provider_information"] = $service_provider_information;
                    return view('report', $view_data);
                }
                else
                    return view('report_finished');
            }
            else{
                return view('report_finished');
            }
        }
        catch(Exception $e){
            return view('report_finished');
        }
        catch(\Illuminate\Database\QueryException $e){
            return view('report_finished');
        }
    }

    private function QuizIsFinished(){

    }
}
