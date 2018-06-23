@extends('admin.layout')
@section('head')
<style type="text/css">
table{
	width: 30em;
    table-layout:fixed;
}
</style>
@stop
@section('content')
<div class="content-page">
	<!-- Start content -->
	<div class="content">
    	<div class="container">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-title-box">
				  		<h4 class="page-title">意見調查表列表</h4>
					</div>
				</div>
      		</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<form action="/admin/report" method="get" class="form-horizontal with-pagination">
			                <div class="row row-m">
			                	<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">訂單編號</label>
										<div class="col-md-8">
											<input type="text" name="id" value="{{ $request->id }}" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">顧客名稱</label>
										<div class="col-md-8">
											<input type="text" name="name" value="{{ $request->name }}" class="form-control">
										</div>
									</div>
								</div>
								<!-- /col-md-4-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">選擇方案</label>
										<div class="col-md-8">
											<select name="service" class="form-control">
												<option selected="true" value="">選擇方案</option>
												@foreach($service_list as $service)
												<option value="{{ $service->id }}" <?php if($request->service == $service->id){?> selected <?php }?>>{{ $service->title }}</option>
												@endforeach
											</select>
										</div>
									</div>
								<!-- /form-group-->
								</div>
			                </div>
			                <!-- /row-->
			                <div class="row row-m">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">手機號碼</label>
										<div class="col-md-8">
											<input type="text" name="phone" class="form-control">
										</div>
									</div>
								<!-- /form-group-->
								</div>
								<!-- /col-md-4-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">店家</label>
										<div class="col-md-8">
											<select name="shop" class="form-control">
												<option selected="true" value="">選擇店家</option>
												@foreach($shop_list as $shop)
												<option value="{{ $shop->id }}" <?php if($request->shop == $shop->id){?> selected <?php }?>>{{ $shop->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								<!-- /form-group-->
								</div>
			                  <!-- /col-md-4-->
			                  <!-- /col-md-4-->
			                </div>
			                <!-- /row-->
			                <div class="row row-m">
								<div class="col-md-4">
			                    	<div class="form-group">
										<label class="col-md-4 control-label">師傅</label>
										<div class="col-md-8">
			                       			<select name="service_provider" value="0" class="form-control">
												<option selected="true" value="">選擇師傅</option>
												@foreach($service_provider_list as $service_provider)
												<option value="{{ $service_provider['id'] }}"<?php if($request->service_provider == $service_provider['id']){?> selected <?php }?>>{{ $service_provider['name'] }}</option>
												@endforeach
											</select>
			                    		</div>
			                    	</div>
			                    	<!-- /form-group-->
			                  	</div>
			                  <!-- /col-md-4-->
			                  	<div class="col-md-4">
			                    	<div class="form-group">
			                      		<label class="col-md-4 control-label">訂單日期</label>
			                      		<div class="col-md-8">
			                        		<input type="date" name="start_time" class="form-control" value="{{ $request->start_time }}">
			                      		</div>
			                   		</div>
			                    <!-- /form-group-->
			                  	</div>
			                  <!-- /col-md-4-->
				                <div class="col-md-4">
				                    <div class="form-group">
										<label class="col-md-4 control-label text-center">至</label>
				                    	<div class="col-md-8">
				                        	<input type="date" name="end_time" class="form-control" value="{{ $request->end_time }}">
				                    	</div>
				                    </div>
				                    <!-- /form-group-->
				                </div>
			                  <!-- /col-md-4-->
			                  <!-- /col-md-8-->
			                  <div class="col-md-12 text-right"><input class="btn btn-primary" type="submit" value="查詢"></div>
			                  <!-- /col-md-12-->
			                </div>
			                <!-- /row-->
			            </form>
					</div>
				</div>
			</div>

		</div>
        <!-- end container -->
        <div class="container">
        	<div class="card-box">
		        <table class="table table-striped">
					<thead>
						<th>編號</th>
						<th>櫃檯</th>
						<th>櫃台服務態度</th>
						<th>師傅服務態度</th>
						<th>技術</th>
						<th>表現</th>
						<th>特殊情況</th>
						<th>下次來訪</th>
						<th>建議</th>
						<th>時間</th>
						<th></th>
					</thead>
					<tbody>
					@foreach($order_list as $order)
					<tr>
						<td>{{ $order->id }}</td>
						<td>{{ $order->report->q0 }}</td>
						<td>{{ $order->report->q1 }}</td>
						<td>{{ $order->report->q2 }}</td>
						<td>{{ $order->report->q3 }}</td>
						<td>{{ $order->report->q4 }}</td>
						<td style="width:100%;word-break:keep-all;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $order->report->q5 }}</td>
						<td>{{ $order->report->q6 }}</td>
						<td>{{ $order->report->q7 }}</td>
						<td>{{ $order->start_time }}</td>
						<td><button class="btn btn-primary detail" data-order="{{$order}}">明細</button></td>
					</tr>
					@endforeach
					</tbody>
				</table>
				{!! $order_list->appends(['name' => $request->name, 'service' => $request->service, 'phone' => $request->phone, 'shop' => $request->shop, 'service_provider' => $request->service_provider, 'start_time' => $request->start_time, 'end_time' => $request->end_time, ])->links() !!}
			</div>
		</div>
    </div>
    <!-- end content -->
    <!-- FOOTER -->
    <footer class="footer text-right">
    2017 © stevia-network.
    </footer>
	<!-- End FOOTER -->
</div>
@stop
@section('script')
<script id="report_detail" type="x-jsrender">
    <div class="container" style="height:200x;">
        <div class="row">
        	<div class="col-md-2">
        		<h4 style="color:chocolate;">櫃檯:</h4>
        	</div>
        	<div class="col-md-4">
        		<h4>@{{:report.q0}}</h4>
        	</div>
        	<div class="col-md-2">
        		<h4 style="color:chocolate;">櫃檯服務態度:</h4>
        	</div>
        	<div class="col-md-2">
        		<h4>@{{:report.q1}}</h4>
        	</div>
        </div>
        <div class="row">
        	<div class="col-md-2">
        		<h4 style="color:chocolate;">師傅服務態度:</h4>
        	</div>
        	<div class="col-md-4">
        		<h4>@{{:report.q2}}</h4>
        	</div>
        	<div class="col-md-2">
        		<h4 style="color:chocolate;">師傅服務技術:</h4>
        	</div>
        	<div class="col-md-2">
        		<h4>@{{:report.q3}}</h4>
        	</div>
        </div>
        <div class="row">
        	<div class="col-md-2">
        		<h4 style="color:chocolate;">師傅服務表現:</h4>
        	</div>
        	<div class="col-md-4">
        		<h4>@{{:report.q4}}</h4>
        	</div>
        </div>
        <div class="row">
			<div class="col-md-2">
        		<h4 style="color:chocolate;">特殊情況:</h4>
        	</div>
        	<div class="col-md-10">
        		<h4>@{{:report.q5}}</h4>
        	</div>        	
        </div>
        <div class="row">
			<div class="col-md-2">
        		<h4 style="color:chocolate;">下次來訪意願:</h4>
        	</div>
        	<div class="col-md-10">
        		<h4>@{{:report.q6}}</h4>
        	</div>        	
        </div>
        <div class="row">
			<div class="col-md-2">
        		<h4 style="color:chocolate;">建議:</h4>
        	</div>
        	<div class="col-md-10">
        		<h4>@{{:report.q7}}</h4>
        	</div>        	
        </div>
        <hr>
        @{{if report.status == 4}}
        <div class="row">
			<div class="col-md-2">
        		<h4 style="color:chocolate;">回應:</h4>
        	</div>
        	<div class="col-md-10">
        		<h4>@{{:report.response}}</h4>
        	</div>        	
        </div>
        @{{else}}
        <form action="/admin/report/reply" method="post">
        	<input type="hidden" name="order_id" value="@{{:id}}">
        	<input type="hidden" name="phone" value="@{{:phone}}">
        	<div class="row">
	        	<textarea class="form-control" id="response" name="message" required></textarea>
	        </div>
	        <div class="row">
	        	<input type="submit" class="btn btn-primary" value="回覆">
	        </div>
        </form>
        @{{/if}}
    </div>
</script>
<script type="text/javascript">

$(".detail").on('click', function(){
	var order = $(this).data('order');
	var myTemplate = $.templates("#report_detail");
	var html = myTemplate.render(order);
	swal({
        title: '#'+order.id+' 意見調查表',
        html: html,
        width: "90%",
         allowOutsideClick: false,
        showCancelButton: false,
        focusConfirm: false,
        showConfirmButton: false,
        showCloseButton: true,
    }).then((result) => {
    	var response = $("#response").val();
    	if(result && response != ""){
    		console.log("1");
    	}
    });
});
</script>
@stop