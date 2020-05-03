@extends('admin.master')
@section('content')
<div class="container-fluid">

	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="/admin/index">Dashboard</a>
		</li>
		<li class="breadcrumb-item active">Overview</li>
	</ol>

	<!-- Icon Cards-->
	<div class="row">
		<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-primary o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fas fa-fw fa-comments"></i>
					</div>
					<div class="mr-5">26 New Messages!</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
					<span class="float-left">View Details</span>
					<span class="float-right">
						<i class="fas fa-angle-right"></i>
					</span>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-warning o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fas fa-fw fa-list"></i>
					</div>
					<div class="mr-5">11 New Tasks!</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
					<span class="float-left">View Details</span>
					<span class="float-right">
						<i class="fas fa-angle-right"></i>
					</span>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-success o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fas fa-fw fa-shopping-cart"></i>
					</div>
					<div class="mr-5">123 New Orders!</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
					<span class="float-left">View Details</span>
					<span class="float-right">
						<i class="fas fa-angle-right"></i>
					</span>
				</a>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-danger o-hidden h-100">
				<div class="card-body">
					<div class="card-body-icon">
						<i class="fas fa-fw fa-life-ring"></i>
					</div>
					<div class="mr-5">13 New Tickets!</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
					<span class="float-left">View Details</span>
					<span class="float-right">
						<i class="fas fa-angle-right"></i>
					</span>
				</a>
			</div>
		</div>
	</div>

	<!-- Area Chart Example-->
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-chart-area"></i>
		Doanh Thu Cửa Hàng</div>
		<div class="card-body">
			<canvas id="myAreaChart" width="100%" height="30"></canvas>
		</div>
		<!-- 	<div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
	</div>
</div>
<!-- /.container-fluid -->
@endsection
@section('script')
<script type="text/javascript">
	var data = {!! $data !!};
	var dateArray = data.map(function (el) { return el.date; });
	var countArray = data.map(function (el) { return el.totalPrice; });
	console.log(countArray);

	// Set new default font family and font color to mimic Bootstrap's default styling
	Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
	Chart.defaults.global.defaultFontColor = '#292b2c';

	// Area Chart Example
	var ctx = document.getElementById("myAreaChart");
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: dateArray,
			datasets: [{
		      label: "Tổng",
		      lineTension: 0.3,
		      backgroundColor: "rgba(2,117,216,0.2)",
		      borderColor: "rgba(2,117,216,1)",
		      pointRadius: 5,
		      pointBackgroundColor: "rgba(2,117,216,1)",
		      pointBorderColor: "rgba(255,255,255,0.8)",
		      pointHoverRadius: 5,
		      pointHoverBackgroundColor: "rgba(2,117,216,1)",
		      pointHitRadius: 50,
		      pointBorderWidth: 2,
		      data: countArray,
		    }],
		},
		options: {
			scales: {
				xAxes: [{
					time: {
						unit: 'date'
					},
					gridLines: {
						display: false
					},
					ticks: {
						maxTicksLimit: 30
					}
				}],
				yAxes: [{
					ticks: {
						min: 0,
						max: 400000,
						maxTicksLimit: 10
					},
					gridLines: {
						color: "rgba(0, 0, 0, .125)",
					}
				}],
			},
			legend: {
				display: false
			}
		}
	});
</script>
@endsection