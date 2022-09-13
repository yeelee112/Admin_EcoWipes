<?php

	require_once "DataProvider.php";
	require_once "functions.php";

	

	$totalOrderDay = 0;
	$totalEarningDay = 0;
	$totalEarningYesterday = 0;
	$totalEarningLastMonth = 0;

	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$now = date('Y-m-d H:i:s');

	$day = date("d", strtotime($now));
	$month = date("m", strtotime($now));
	$year = date("Y", strtotime($now));
	$lastMonth = date("m", strtotime('first day of last month'));

	$getDayMonthYear = date("d-m-Y", strtotime($now));
	$getYesterdayMonthYear = date("d-m-Y", strtotime("-1 days"));

	$sqlSumOrder = "select SUM(total_price) as total_earning, count(id) as total_order from order_detail where MONTH(created_at) = $month and YEAR(created_at) = $year";
	$listSumOrder = DataProvider::execQuery($sqlSumOrder);
	$rowSumOrder = mysqli_fetch_assoc($listSumOrder);

	$sqlSumOrderEach = "select SUM(total_price) as total_earning, count(id) as total_order from order_detail where MONTH(created_at) = $month and YEAR(created_at) = $year and user_id = 0";
	$listSumOrderEach = DataProvider::execQuery($sqlSumOrderEach);
	$rowSumOrderEach = mysqli_fetch_assoc($listSumOrderEach);

	$totalEarning = $rowSumOrder["total_earning"];
	$totalOrder = $rowSumOrder["total_order"];

	$guestEarning = $rowSumOrderEach["total_earning"];
	$guestOrder = $rowSumOrderEach["total_order"];

	$userOrder = $totalOrder - $guestOrder;
	$userEarning = $totalEarning - $guestEarning;

	for ($i = 0; $i < 12; $i++) {
		$temp = $i * 2;
		$temp2 = $i * 2 + 2;

		if($i * 2 < 10){
			$temp = '0'.$i * 2;
		}
		if($i * 2 + 2 < 10){
			$temp2 = '0'.$i * 2 + 2;
		}
			
		$sqlDay = "select SUM(total_price) as total_earning_at_time, count(id) as total_order_at_time from order_detail where DATE_FORMAT(created_at, '%T %d-%m-%Y') > '".$temp.":00:00 ".$getDayMonthYear."' and DATE_FORMAT(created_at, '%T %d-%m-%Y') < '".$temp2.":00:00 ".$getDayMonthYear."' and DATE_FORMAT(created_at, '%d-%m-%Y') = '$getDayMonthYear'";

		$listDay = DataProvider::execQuery($sqlDay);
		$rowDay = mysqli_fetch_array($listDay);
		if ($rowDay[0] < 1 && ($i * 2) <= date("H", strtotime($now))) {
			$listEarningDay[] = '0';
			$listOrderDay[] = '0';
		} 
		else {
			$listEarningDay[] = $rowDay["total_earning_at_time"];
			$listOrderDay[] = $rowDay["total_order_at_time"];
		}
		$totalOrderDay += $rowDay["total_order_at_time"];
		$totalEarningDay += $rowDay["total_earning_at_time"];

		$sqlYesterday = "select SUM(total_price) as total_earning_at_time from order_detail where DATE_FORMAT(created_at, '%T %d-%m-%Y') > '".$temp.":00:00 ".$getYesterdayMonthYear."' and DATE_FORMAT(created_at, '%T %d-%m-%Y') < '".$temp2.":00:00 ".$getYesterdayMonthYear."' and DATE_FORMAT(created_at, '%d-%m-%Y') = '$getYesterdayMonthYear'";

		$listYesterday = DataProvider::execQuery($sqlYesterday);
		$rowYesterday = mysqli_fetch_array($listYesterday);
		if ($rowYesterday[0] < 1) {
			$listEarningYesterday[] = '0';
		} 
		else {
			$listEarningYesterday[] = $rowYesterday["total_earning_at_time"];
		}
		$totalEarningYesterday += $rowYesterday["total_earning_at_time"];
	}

	$listJSONEarningDay = json_encode($listEarningDay);
	$listJSONEarningYesterday = json_encode($listEarningYesterday);
	$listJSONOrderDay = json_encode($listOrderDay);

	$listDayOfMonth = getDateInMonth($month, $year);

	foreach($listDayOfMonth as $value){
		$sqlEarningPerDay = "select SUM(total_price) as total_earning_at_time from order_detail where DATE_FORMAT(created_at, '%d/%m') = '$value' and DATE_FORMAT(created_at, '%m-%Y') = '$month-$year'";

		$listlEarningPerDay = DataProvider::execQuery($sqlEarningPerDay);
		$rowlEarningPerDay = mysqli_fetch_array($listlEarningPerDay);

		if ($rowlEarningPerDay[0] < 1 && $value <= $day.'-'.$month) {
			$listEarningPerDay[] = '0';
		}
		else{
			$listEarningPerDay[] = $rowlEarningPerDay["total_earning_at_time"];
		}
	}

	$listDayOfLastMonth = getDateInMonth($month - 1, $year);

	foreach($listDayOfLastMonth as $value){
		$sqlEarningPerDayLastMonth = "select SUM(total_price) as total_earning_at_time from order_detail where DATE_FORMAT(created_at, '%d/%m') = '$value' and DATE_FORMAT(created_at, '%m-%Y') = '$lastMonth-$year'";

		$listlEarningPerDayLastMonth = DataProvider::execQuery($sqlEarningPerDayLastMonth);
		$rowlEarningPerDayLastMonth = mysqli_fetch_array($listlEarningPerDayLastMonth);

		if ($rowlEarningPerDayLastMonth[0] < 1) {
			$listEarningPerDayLastMonth[] = '0';
		}
		else{
			$listEarningPerDayLastMonth[] = $rowlEarningPerDayLastMonth["total_earning_at_time"];
			$totalEarningLastMonth += $rowlEarningPerDayLastMonth["total_earning_at_time"];
		}
		
	}

	$listJSEarningPerDayLastMonth = json_encode($listEarningPerDayLastMonth);
	$listJSEarningPerDay = json_encode($listEarningPerDay);

	$listJSDayOfMonth = json_encode($listDayOfMonth);
	
	//returns '180]'
	//from the last occurrence of '['
?>
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<title>Admin Dashboard | EcoWipes E-commerce</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:locale" content="en_US">
	<meta property="og:type" content="article">
	<link rel="shortcut icon" href="../assets/media/logos/favicon.svg">
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
	<link href="../assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css">
	<link href="../assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css">
	<link href="../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">
	<link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css">
</head>
<!--end::Head-->
<!--begin::Body-->

<body data-kt-name="metronic" id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<!--begin::Theme mode setup on page load-->
	<script>
		if (document.documentElement) {
			const defaultThemeMode = "system";
			const name = document.body.getAttribute("data-kt-name");
			let themeMode = localStorage.getItem("kt_" + (name !== null ? name + "_" : "") + "theme_mode_value");
			if (themeMode === null) {
				if (defaultThemeMode === "system") {
					themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
				} else {
					themeMode = defaultThemeMode;
				}
			}
			document.documentElement.setAttribute("data-theme", themeMode);
		}
	</script>
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/left-header.php" ?>
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/top-header.php" ?>
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<div class="toolbar" id="kt_toolbar">
						<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
							<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
								<h1 class="d-flex align-items-center text-dark fw-bold my-1 fs-3">eCommerce Dashboard</h1>
							</div>
							<div class="d-flex align-items-center py-1">
								Hallo buổi sáng tốt nành
							</div>
						</div>
					</div>
					<div class="post d-flex flex-column-fluid" id="kt_post">
						<div id="kt_content_container" class="container-xxl">
							<div class="row g-5 g-xl-10 mb-xl-10">
								<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
									<div class="card card-flush h-md-50 mb-5 mb-xl-10">
										<div class="card-header pt-5">
											<div class="card-title d-flex flex-column">
												<div class="d-flex align-items-center">
													<span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₫</span>
													<span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2"><?php echo number_format($totalEarning, 0, ",", "."); ?></span>
													<!-- Increase -->
													<?php
														if($totalEarning != 0){
															$rateEarningMonth = (($totalEarningLastMonth - $totalEarning) * 100) / $totalEarning;
															if ($rateEarningMonth < 0){ ?>
																<span class="badge badge-light-success fs-base">
																	<span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
																			<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
																		</svg>
																	</span>
																	<?php echo number_format(abs($rateEarningMonth), 1, '.', ''); ?>%
																</span>
														<?php } 
															else {?>
																<span class="badge badge-light-danger fs-base">
																	<span class="svg-icon svg-icon-5 svg-icon-danger ms-n1">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
																			<path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
																		</svg>
																	</span>
																	<?php echo number_format(abs($rateEarningMonth), 1, '.', ''); ?>%
																</span>
														<?php 
															}
														} ?>
												</div>
												<span class="text-gray-400 pt-1 fw-semibold fs-6">Tổng doanh thu - <?php echo date('m/Y', strtotime($now)); ?></span>
											</div>
										</div>
										<div class="card-body pt-2 pb-4 d-flex align-items-center">
											<div class="d-flex flex-center me-5 pt-2">
												<div id="kt_card_widget_4_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
											</div>
											<div class="d-flex flex-column content-justify-center w-100">
												<div class="d-flex fs-6 fw-semibold align-items-center">
													<div class="bullet w-8px h-6px rounded-2 bg-main-color me-3"></div>
													<div class="text-gray-500 flex-grow-1 me-4">Khách</div>
													<div class="fw-bolder text-gray-700 text-xxl-end"><?php if($guestEarning == 0){ echo 0;} else{ echo shortifyCurrencyXtream($guestEarning);} ?> <span class="money-sign-small text-gray-400">₫</span></div>
												</div>
												<div class="d-flex fs-6 fw-semibold align-items-center my-3">
													<div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
													<div class="text-gray-500 flex-grow-1 me-4">User</div>
													<div class="fw-bolder text-gray-700 text-xxl-end"><?php echo shortifyCurrencyXtream($userEarning) ?>  <span class="money-sign-small text-gray-400">₫</span></div>
												</div>
											</div>
										</div>
									</div>
									<div class="card card-flush h-md-50 mb-xl-10">
										<div class="card-header pt-5">
											<div class="card-title d-flex flex-column">
												<div class="d-flex align-items-center">
													<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 512 512;width: 24px;height: 24px;" xml:space="preserve" class="">
														<g>
															<g xmlns="http://www.w3.org/2000/svg">
																<g>
																	<path d="M476.919,141.304l-0.379-0.655c-0.525-1.089-1.131-2.132-1.807-3.123L403.504,14.38C398.373,5.51,388.821,0,378.574,0    H133.437c-10.263,0-19.823,5.523-24.952,14.417l-72.39,125.586c-0.652,1.132-1.122,2.316-1.454,3.519    c-1.019,2.751-1.579,5.724-1.579,8.826v324.365c0,19.456,15.829,35.285,35.285,35.285h375.304    c19.456,0,35.285-15.829,35.285-35.285V151.159c0-0.435-0.013-0.867-0.036-1.297C479.104,146.975,478.478,143.998,476.919,141.304    z M271.518,30.011h106.358l56.032,96.873h-162.39V30.011z M134.136,30.011h107.371v96.873H78.296L134.136,30.011z     M448.925,476.714c0,2.908-2.366,5.274-5.274,5.274H68.347c-2.908,0-5.274-2.366-5.274-5.274V156.895h385.852V476.714z" fill="#B5B5C3" data-original="#000000"></path>
																</g>
															</g>
															<g xmlns="http://www.w3.org/2000/svg">
																<g>
																	<path d="M324.115,270.026c-5.859-5.859-15.361-5.859-21.221,0l-67.002,67.002l-25.764-25.764c-5.859-5.859-15.361-5.859-21.221,0    c-5.86,5.859-5.86,15.361,0,21.221l36.376,36.374c2.93,2.93,6.771,4.396,10.61,4.396c3.839,0,7.681-1.466,10.61-4.396    l77.612-77.612C329.975,285.387,329.975,275.886,324.115,270.026z" fill="#B5B5C3" data-original="#000000"></path>
																</g>
															</g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
															<g xmlns="http://www.w3.org/2000/svg"></g>
														</g>
													</svg>
													<span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2 ms-2"><?php echo number_format($totalOrder, 0, ",", "."); ?></span>
													<span class="badge badge-light-danger fs-base">
														<span class="svg-icon svg-icon-5 svg-icon-danger ms-n1">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
																<path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor" />
															</svg>
														</span>
														0%</span>
												</div>
												<span class="text-gray-400 pt-1 fw-semibold fs-6">Tổng đơn hàng - <?php echo date('m/Y', strtotime($now)); ?></span>
											</div>
										</div>
										<div class="card-body pt-2 pb-4 d-flex align-items-center">
											<div class="d-flex flex-center me-5 pt-2">
												<div id="kt_card_widget_5_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
											</div>
											<div class="d-flex flex-column content-justify-center w-100">
												<div class="d-flex fs-6 fw-semibold align-items-center">
													<div class="bullet w-8px h-6px rounded-2 bg-main-color me-3"></div>
													<div class="text-gray-500 flex-grow-1 me-4">Khách</div>
													<div class="fw-bolder text-gray-700 text-xxl-end"><?php echo number_format($guestOrder, 0, ".", ","); ?> đơn</div>
												</div>
												<div class="d-flex fs-6 fw-semibold align-items-center my-3">
													<div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
													<div class="text-gray-500 flex-grow-1 me-4">User</div>
													<div class="fw-bolder text-gray-700 text-xxl-end"><?php echo number_format($userOrder, 0, ".", ","); ?> đơn</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
									<div class="card card-flush h-md-50 mb-5 mb-xl-10">
										<div class="card-header pt-5">
											<div class="card-title d-flex flex-column">
												<div class="d-flex align-items-center">
													<span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₫</span>
													<span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2"><?php echo number_format($totalEarningDay, 0, ",", "."); ?></span>
													<!-- <span class="badge badge-light-danger fs-base">
														<span class="svg-icon svg-icon-5 svg-icon-danger ms-n1">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
																<path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
															</svg>
														</span>
														2.2%</span> -->
													<!-- <span class="badge badge-light-success fs-base">
														<span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
																<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
															</svg>
														</span>
														2.6%</span> -->
														<?php
														if($totalEarningDay != 0){
															$rateEarning = (($totalEarningYesterday - $totalEarningDay) * 100) / $totalEarningDay;
															if ($rateEarning < 0){ ?>
																<span class="badge badge-light-success fs-base">
																	<span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
																			<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
																		</svg>
																	</span>
																	<?php echo number_format(abs($rateEarning), 1, '.', ''); ?>%
																</span>
														<?php } 
															else {?>
																<span class="badge badge-light-danger fs-base">
																	<span class="svg-icon svg-icon-5 svg-icon-danger ms-n1">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
																			<path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
																		</svg>
																	</span>
																	<?php echo number_format(abs($rateEarning), 1, '.', ''); ?>%
																</span>
														<?php 
															}
														} ?>
												</div>
												<span class="text-gray-400 pt-1 fw-semibold fs-6">Doanh thu - <?php echo date('d/m/Y', strtotime($now)); ?></span>
											</div>
										</div>
										<div class="card-body d-flex align-items-end px-0 pb-0">
											<div id="kt_card_widget_1_chart" class="w-100" style="height: 80px"></div>
										</div>
									</div>
									<div class="card card-flush h-md-50 mb-xl-10">
										<div class="card-header pt-5">
											<div class="card-title d-flex flex-column">
												<span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2"><?php echo number_format($totalOrderDay, 0, ",", "."); ?></span>
												<span class="text-gray-400 pt-1 fw-semibold fs-6">Đơn hàng - <?php echo date('d/m/Y', strtotime($now)); ?></span>
											</div>
										</div>
										<div class="card-body d-flex align-items-end px-0 pb-0">
											<div id="kt_card_widget_6_chart" class="w-100" style="height: 80px"></div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
									<div class="card card-flush overflow-hidden h-md-100">
										<div class="card-header py-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bold text-dark">Doanh thu trong tháng</span>
												<span class="text-gray-400 mt-1 fw-semibold fs-6">Tháng <?php echo date('m/Y', strtotime($now)); ?></span>
											</h3>
											<div class="card-toolbar">
												<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
													<span class="svg-icon svg-icon-1">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor" />
															<rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
															<rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
															<rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor" />
														</svg>
													</span>
												</button>
												<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
													<!-- <div class="menu-item px-3">
															<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
														</div>
														<div class="separator mb-3 opacity-75"></div>
														<div class="menu-item px-3">
															<a href="#" class="menu-link px-3">New Ticket</a>
														</div>
														<div class="menu-item px-3">
															<a href="#" class="menu-link px-3">New Customer</a>
														</div>
														<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
															<a href="#" class="menu-link px-3">
																<span class="menu-title">New Group</span>
																<span class="menu-arrow"></span>
															</a>
															<div class="menu-sub menu-sub-dropdown w-175px py-4">
																<div class="menu-item px-3">
																	<a href="#" class="menu-link px-3">Admin Group</a>
																</div>
																<div class="menu-item px-3">
																	<a href="#" class="menu-link px-3">Staff Group</a>
																</div>
																<div class="menu-item px-3">
																	<a href="#" class="menu-link px-3">Member Group</a>
																</div>
															</div>
														</div>
														<div class="menu-item px-3">
															<a href="#" class="menu-link px-3">New Contact</a>
														</div>
														<div class="separator mt-3 opacity-75"></div>
														<div class="menu-item px-3">
															<div class="menu-content px-3 py-3">
																<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
															</div>
														</div> -->
												</div>
											</div>
										</div>
										<div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
											<div class="px-9 mb-5">
												<div class="d-flex mb-2">
													<span class="fs-4 fw-semibold text-gray-400 me-1">₫</span>
													<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2"><?php echo number_format($totalEarning, 0, ",", "."); ?></span>
												</div>
												<span class="fs-6 fw-semibold text-gray-400">
													<?php 
														if ($rateEarningMonth < 0){
															echo "Tăng ".number_format(abs($rateEarningMonth), 1, '.', '')."% so với tháng trước"; 
														}
														else{
															echo "Giảm ".number_format(abs($rateEarningMonth), 1, '.', '')."% so với tháng trước";  
														}
													?>
											</div>
											<div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6" style="height: 300px"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row gy-5 g-xl-10">
								<div class="col-xl-12 mb-5 mb-xl-10">
									<div class="card card-flush h-xl-100">
										<div class="card-header pt-7">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bold text-gray-800">Đơn hàng</span>
												<span class="text-gray-400 mt-1 fw-semibold fs-6"><a href="/apps/ecommerce/sales/order">Xem tất cả đơn hàng</a></span>
											</h3>
											<div class="card-toolbar">
												<div class="d-flex flex-stack flex-wrap gap-4">
													<div class="d-flex align-items-center fw-bold">
														<div class="text-gray-400 fs-7 me-2" style="white-space:nowrap;">Trạng thái</div>
														<select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-200px" data-placeholder="Select an option" data-kt-table-widget-4="filter_status">
															<option></option>
															<option value="Show All" selected="selected">Hiển thị tất cả</option>
															<option value="Đã hoàn thành">Đã hoàn thành</option>
															<option value="Đã xác nhận">Đã xác nhận</option>
															<option value="Đã Từ chối">Đã Từ chối</option>
															<option value="Chờ xác nhận">Chờ xác nhận</option>
														</select>
													</div>
													<div class="position-relative my-1">
														<span class="svg-icon svg-icon-2 position-absolute top-50 translate-middle-y ms-4">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
																<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
															</svg>
														</span>
														<input type="text" data-kt-table-widget-4="search" class="form-control w-300px fs-7 ps-12" placeholder="Search">
													</div>
												</div>
											</div>
										</div>
										<div class="card-body pt-2">
											<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
												<thead>
													<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
														<th class="min-w-100px">Mã Đơn hàng</th>
														<th class="text-end min-w-100px">Đặt vào</th>
														<th class="text-end min-w-125px">Khách hàng</th>
														<th class="text-end min-w-100px">Tổng tiền</th>
														<th class="text-end min-w-100px">Tỉnh/Thành</th>
														<th class="text-end min-w-50px">Trạng thái</th>
														<th class="text-end"></th>
													</tr>
												</thead>
												<tbody class="fw-bold text-gray-600">
													<tr data-kt-table-widget-4="subtable_template" class="d-none">
														<td colspan="2">
															<div class="d-flex align-items-center gap-3">
																<a href="#" class="symbol symbol-50px bg-secondary bg-opacity-25 rounded">
																	<img src data-kt-src-path="" alt data-kt-table-widget-4="template_image">
																</a>
																<div class="d-flex flex-column text-muted">
																	<a href="#" class="text-gray-800 text-hover-primary fw-bold" data-kt-table-widget-4="template_name">Product name</a>
																	<!-- <div class="fs-7" data-kt-table-widget-4="template_description">Product description</div> -->
																</div>
															</div>
														</td>
														<td class="text-end">
															<div class="text-gray-800 fs-7">Số lượng</div>
															<div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_qty">1</div>
														</td>
														<td class="text-end">
															<div class="text-gray-800 fs-7">Tổng</div>
															<div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_total">name</div>
														</td>
														<td class="text-end">
															<div class="text-gray-800 fs-7 me-3">Tồn</div>
															<div class="text-muted fs-7 fw-bold" data-kt-table-widget-4="template_stock">32</div>
														</td>
														<td></td>
													</tr>
													<?php 
														require_once 'DataProvider.php';

														$sqlOrder = "select * from order_detail od order by created_at DESC LIMIT 10";
														$listOrder = DataProvider::execQuery($sqlOrder);
														while ($rowOrder = mysqli_fetch_array($listOrder, MYSQLI_ASSOC)) {
													?>
													<tr>
														<td>
															<a href="#" class="text-main">#<?php echo $rowOrder["id"] ?></a>
														</td>
														<td class="text-end"><?php echo time_elapsed_string($rowOrder["created_at"]); ?></td>
														<td class="text-end">
															<a href="#" class="text-gray-600 text-hover-primary"><?php echo $rowOrder["fullname"]?></a>
														</td>
														<td class="text-end"><?php echo number_format($rowOrder["total_price"] + $rowOrder["shipping_fee"], 0, ".", ",") ?> đ</td>
														<td class="text-end">
															<span class="text-gray-800 fw-bolder"><?php echo after_last ('-', $rowOrder["address"]); ?></span>
														</td>
														<td class="text-end">
															<?php if($rowOrder["status_order"] == 0){
																echo '<span class="badge py-3 px-4 fs-7 badge-light-warning">Chờ xác nhận</span>';
															} ?>
														</td>
														<td class="text-end">
															<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row" value="<?php echo $rowOrder["id"] ?>">
																<span class="svg-icon svg-icon-3 m-0 toggle-off">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
																		<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
																	</svg>
																</span>
																<span class="svg-icon svg-icon-3 m-0 toggle-on">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
																	</svg>
																</span>
															</button>
														</td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="row gy-5 g-xl-10">
								<div class="col-xl-4">
									<div class="card card-flush h-lg-100">
										<div class="card-header pt-7 mb-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bold text-gray-800">Top sản phẩm bán chạy</span>
												<span class="text-gray-400 mt-1 fw-semibold fs-6">5 sản phẩm hàng đầu</span>
											</h3>
											<div class="card-toolbar">
												<a href="../apps/ecommerce/sales/listing.html" class="btn btn-sm btn-light">Xem thêm</a>
											</div>
										</div>
										<div class="card-body pt-0">
											<?php 
												require_once 'DataProvider.php';
												$sqlBestSale = "select p.product_name, ot.sum_product_sold, ip.img_thumb, p.price from product p join (select ot.product_id, SUM(ot.quantity) as sum_product_sold from order_items ot group by ot.product_id) ot, image_product ip where p.id = ot.product_id and p.id = ip.product_id order by ot.sum_product_sold DESC LIMIT 5";
												$listBestSale = DataProvider::execQuery($sqlBestSale);
												while ($rowBestSale = mysqli_fetch_array($listBestSale, MYSQLI_ASSOC)) {
											?>
											<div class="d-flex flex-stack">
												<img src="<?php echo $rowBestSale["img_thumb"] ?>" class="me-4 w-40px" style="border-radius: 4px" alt>
												<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
													<div class="me-5">
														<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">1. <?php echo $rowBestSale["product_name"] ?></a>
														<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0"><?php echo number_format($rowBestSale["price"], 0, ".", ",") ?> đ</span>
													</div>
													<div class="d-flex align-items-center">
														<span class="text-gray-800 fw-bold fs-6 me-3 d-block"><?php echo $rowBestSale["sum_product_sold"] ?></span>
														<!-- <div class="m-0">
															<span class="badge badge-light-success fs-base">
																<span class="svg-icon svg-icon-5 svg-icon-success ms-n1">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
																		<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
																	</svg>
																</span>
																2.6%</span>
														</div> -->
													</div>
												</div>
											</div>
											<div class="separator separator-dashed my-3"></div>
											<?php } ?>
										</div>
									</div>
								</div>
								<!-- <div class="col-xl-8">
									<div class="card card-flush h-xl-100">
										<div class="card-header pt-7">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bold text-dark">Stock Report</span>
												<span class="text-gray-400 mt-1 fw-semibold fs-6">Total 2,356 Items in the Stock</span>
											</h3>
											<div class="card-toolbar">
												<div class="d-flex flex-stack flex-wrap gap-4">
													<div class="d-flex align-items-center fw-bold">
														<div class="text-muted fs-7 me-2">Cateogry</div>
														<select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option">
															<option></option>
															<option value="Show All" selected="selected">Show All</option>
															<option value="a">Category A</option>
															<option value="b">Category B</option>
														</select>
													</div>
													<div class="d-flex align-items-center fw-bold">
														<div class="text-muted fs-7 me-2">Status</div>
														<select class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" data-control="select2" data-hide-search="true" data-dropdown-css-class="w-150px" data-placeholder="Select an option" data-kt-table-widget-5="filter_status">
															<option></option>
															<option value="Show All" selected="selected">Show All</option>
															<option value="In Stock">In Stock</option>
															<option value="Out of Stock">Out of Stock</option>
															<option value="Low Stock">Low Stock</option>
														</select>
													</div>
													<a href="../apps/ecommerce/catalog/products.html" class="btn btn-light btn-sm">View Stock</a>
												</div>
											</div>
										</div>
										<div class="card-body">
											<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
												<thead>
													<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
														<th class="min-w-100px">Item</th>
														<th class="text-end pe-3 min-w-100px">Product ID</th>
														<th class="text-end pe-3 min-w-150px">Date Added</th>
														<th class="text-end pe-3 min-w-100px">Price</th>
														<th class="text-end pe-3 min-w-50px">Status</th>
														<th class="text-end pe-0 min-w-25px">Qty</th>
													</tr>
												</thead>
												<tbody class="fw-bold text-gray-600">
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Macbook Air M1</a>
														</td>
														<td class="text-end">#XGY-356</td>
														<td class="text-end">02 Apr, 2022</td>
														<td class="text-end">$1,230</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
														</td>
														<td class="text-end" data-order="58">
															<span class="text-dark fw-bold">58 PCS</span>
														</td>
													</tr>
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Surface Laptop 4</a>
														</td>
														<td class="text-end">#YHD-047</td>
														<td class="text-end">01 Apr, 2022</td>
														<td class="text-end">$1,060</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
														</td>
														<td class="text-end" data-order="0">
															<span class="text-dark fw-bold">0 PCS</span>
														</td>
													</tr>
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Logitech MX 250</a>
														</td>
														<td class="text-end">#SRR-678</td>
														<td class="text-end">24 Mar, 2022</td>
														<td class="text-end">$64</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
														</td>
														<td class="text-end" data-order="290">
															<span class="text-dark fw-bold">290 PCS</span>
														</td>
													</tr>
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">AudioEngine HD3</a>
														</td>
														<td class="text-end">#PXF-578</td>
														<td class="text-end">24 Mar, 2022</td>
														<td class="text-end">$1,060</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
														</td>
														<td class="text-end" data-order="46">
															<span class="text-dark fw-bold">46 PCS</span>
														</td>
													</tr>
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">HP Hyper LTR</a>
														</td>
														<td class="text-end">#PXF-778</td>
														<td class="text-end">16 Jan, 2022</td>
														<td class="text-end">$4500</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
														</td>
														<td class="text-end" data-order="78">
															<span class="text-dark fw-bold">78 PCS</span>
														</td>
													</tr>
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Dell 32 UltraSharp</a>
														</td>
														<td class="text-end">#XGY-356</td>
														<td class="text-end">22 Dec, 2022</td>
														<td class="text-end">$1,060</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-warning">Low Stock</span>
														</td>
														<td class="text-end" data-order="8">
															<span class="text-dark fw-bold">8 PCS</span>
														</td>
													</tr>
													<tr>
														<td>
															<a href="../apps/ecommerce/catalog/edit-product.html" class="text-dark text-hover-primary">Google Pixel 6 Pro</a>
														</td>
														<td class="text-end">#XVR-425</td>
														<td class="text-end">27 Dec, 2022</td>
														<td class="text-end">$1,060</td>
														<td class="text-end">
															<span class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
														</td>
														<td class="text-end" data-order="124">
															<span class="text-dark fw-bold">124 PCS</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div> -->
							</div>
						</div>
					</div>
				</div>
				<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
					<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
						<div class="text-dark order-2 order-md-1">
							<span class="text-muted fw-semibold me-1">2022©</span>
							<a href target="_blank" class="text-gray-800 text-hover-primary">EcoWipes Việt Nam</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<span class="svg-icon">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
				<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
			</svg>
		</span>
	</div>

	<script>
		var hostUrl = "/metronic8/demo13/assets/";
	</script>
	<script src="../assets/plugins/global/plugins.bundle.js"></script>
	<script src="../assets/js/scripts.bundle.js"></script>
	<script src="../assets/plugins/custom/datatables/datatables.bundle.js"></script>
	<script src="../assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
	<script src="../assets/js/widgets.bundle.js"></script>
	<script src="../assets/js/custom/widgets.js"></script>
	<script src="../assets/js/custom/apps/chat/chat.js"></script>
	<script src="../assets/js/custom/intro.js"></script>
	<script src="../assets/js/custom/utilities/modals/upgrade-plan.js"></script>
	<script src="../assets/js/custom/utilities/modals/create-app.js"></script>
	<script src="../assets/js/custom/utilities/modals/users-search.js"></script>

	<script>
		"use strict";

		// Class definition
		var KTCardsWidget4 = function() {
			// Private methods
			var initChart = function() {
				var el = document.getElementById('kt_card_widget_4_chart');

				if (!el) {
					return;
				}

				var options = {
					size: el.getAttribute('data-kt-size') ? parseInt(el.getAttribute('data-kt-size')) : 70,
					lineWidth: el.getAttribute('data-kt-line') ? parseInt(el.getAttribute('data-kt-line')) : 11,
					rotate: el.getAttribute('data-kt-rotate') ? parseInt(el.getAttribute('data-kt-rotate')) : 180,
					//percent:  el.getAttribute('data-kt-percent') ,
				}

				var canvas = document.createElement('canvas');
				var span = document.createElement('span');

				if (typeof(G_vmlCanvasManager) !== 'undefined') {
					G_vmlCanvasManager.initElement(canvas);
				}

				var ctx = canvas.getContext('2d');
				canvas.width = canvas.height = options.size;

				el.appendChild(span);
				el.appendChild(canvas);

				ctx.translate(options.size / 2, options.size / 2); // change center
				ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI); // rotate -90 deg

				//imd = ctx.getImageData(0, 0, 240, 240);
				var radius = (options.size - options.lineWidth) / 2;

				var drawCircle = function(color, lineWidth, percent) {
					percent = Math.min(Math.max(0, percent || 1), 1);
					ctx.beginPath();
					ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
					ctx.strokeStyle = color;
					ctx.lineCap = 'butt'; // butt, round or square
					ctx.lineWidth = lineWidth
					ctx.stroke();
				};

				// Init 
				drawCircle(KTUtil.getCssVariableValue('--main-color'), options.lineWidth, 1 / 1);
				drawCircle(KTUtil.getCssVariableValue('--kt-primary'), options.lineWidth, <?php echo $userEarning / $totalEarning; ?>);
			}

			// Public methods
			return {
				init: function() {
					initChart();
				}
			}
		}();

		if (typeof module !== 'undefined') {
			module.exports = KTCardsWidget4;
		}

		// On document ready
		KTUtil.onDOMContentLoaded(function() {
			KTCardsWidget4.init();
		});
	</script>

	<script>
		"use strict";

		// Class definition
		var KTCardsWidget5 = function() {
			// Private methods
			var initChart = function() {
				var el = document.getElementById('kt_card_widget_5_chart');

				if (!el) {
					return;
				}

				var options = {
					size: el.getAttribute('data-kt-size') ? parseInt(el.getAttribute('data-kt-size')) : 70,
					lineWidth: el.getAttribute('data-kt-line') ? parseInt(el.getAttribute('data-kt-line')) : 11,
					rotate: el.getAttribute('data-kt-rotate') ? parseInt(el.getAttribute('data-kt-rotate')) : 200,
					//percent:  el.getAttribute('data-kt-percent') ,
				}

				var canvas = document.createElement('canvas');
				var span = document.createElement('span');

				if (typeof(G_vmlCanvasManager) !== 'undefined') {
					G_vmlCanvasManager.initElement(canvas);
				}

				var ctx = canvas.getContext('2d');
				canvas.width = canvas.height = options.size;

				el.appendChild(span);
				el.appendChild(canvas);

				ctx.translate(options.size / 2, options.size / 2); // change center
				ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI); // rotate -90 deg

				//imd = ctx.getImageData(0, 0, 240, 240);
				var radius = (options.size - options.lineWidth) / 2;

				var drawCircle = function(color, lineWidth, percent) {
					percent = Math.min(Math.max(0, percent || 1), 1);
					ctx.beginPath();
					ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
					ctx.strokeStyle = color;
					ctx.lineCap = 'butt'; // butt, round or square
					ctx.lineWidth = lineWidth
					ctx.stroke();
				};

				// Init 
				drawCircle(KTUtil.getCssVariableValue('--main-color'), options.lineWidth, 1 / 1);
				drawCircle(KTUtil.getCssVariableValue('--kt-primary'), options.lineWidth, <?php echo $userOrder / $totalOrder; ?>);
			}

			// Public methods
			return {
				init: function() {
					initChart();
				}
			}
		}();

		if (typeof module !== 'undefined') {
			module.exports = KTCardsWidget4;
		}

		// On document ready
		KTUtil.onDOMContentLoaded(function() {
			KTCardsWidget5.init();
		});
	</script>
	<script>
		"use strict";

		// Class definition
		var KTCardsWidget1 = function () {
			// Private methods
			var initChart = function() {
				var element = document.getElementById("kt_card_widget_1_chart");
				
				if (!element) {
					return;
				}

				var color = element.getAttribute('data-kt-chart-color');
				
				var height = parseInt(KTUtil.css(element, 'height'));
				var labelColor = KTUtil.getCssVariableValue('--kt-gray-500');         
				var baseColor = KTUtil.isHexColor(color) ? color : KTUtil.getCssVariableValue('--main-color');
				var secondaryColor = KTUtil.getCssVariableValue('--kt-success');         

				
				var options = {
					series: [{
						name: 'Doanh thu hôm qua',
						data:<?php echo $listJSONEarningYesterday ?>,
						margin: {
							left: 5,
							right: 5
						}
					},
					{
						name: 'Doanh thu hôm nay',
						data:<?php echo $listJSONEarningDay ?>,
						margin: {
							left: 5,
							right: 5
						}   
					}],
						chart: {
						height: 150,
						type: 'area',
						toolbar: {
							show: false
						},
					},
					legend: {
						show: false
					},
					fill: {
						type: "gradient",
						gradient: {
							shadeIntensity: 1,
							inverseColors: false,
							opacityFrom: 0.45,
							opacityTo: 0.05,
							stops: [20, 100, 100, 100]
						}
					},
					grid: {
						borderColor: "#555",
						clipMarkers: false,
						yaxis: {
						lines: {
							show: false
						}
						}
					},
					stroke: {
						curve: 'smooth',
						show: true,
						width: 1,
						colors: ['#c4c4c4','#009ef7'],
					},
					xaxis: {                 
						axisBorder: {
							show: false,
						},
						categories: ['02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '00:00'],
						axisTicks: {
							show: false
						},
						labels: {
							show: false
						},
						crosshairs: {
							position: 'front',
							stroke: {
								color: baseColor,
								width: 1,
								dashArray: 3
							}
						},
						tooltip: {
							enabled: true,
							formatter: undefined,
							offsetY: 0,
							style: {
								fontSize: '12px'
							}
						}
					},
					tooltip: {
						y: {
							formatter: function (val) {
								return new Intl.NumberFormat('vi-VN', {
									style: 'currency',
									currency: 'VND'
								}).format(val);
							}
						}
					},
					yaxis: {
						labels: {
							show: false
						},
						labels: {
							show: false
						},
					},
					dataLabels: {
						enabled: false
					},
					colors: ['#c4c4c4','#009ef7'],
				};

				// Set timeout to properly get the parent elements width
				var chart = new ApexCharts(element, options);
				
				// Set timeout to properly get the parent elements width
				setTimeout(function() {
					chart.render();   
				}, 300);  
			}

			// Public methods
			return {
				init: function () {
					initChart();
				}   
			}
		}();

		// Webpack support
		if (typeof module !== 'undefined') {
			module.exports = KTCardsWidget1;
		}

		// On document ready
		KTUtil.onDOMContentLoaded(function() {
			KTCardsWidget1.init();
		});
		
	</script>
	<script>
		"use strict";

		var KTCardsWidget6 = function() {
			// Private methods
			var initChart = function() {
				var element = document.getElementById("kt_card_widget_6_chart");

				if (!element) {
					return;
				}

				var height = parseInt(KTUtil.css(element, 'height'));
				var labelColor = KTUtil.getCssVariableValue('--kt-gray-500');
				var borderColor = KTUtil.getCssVariableValue('--kt-border-dashed-color');
				var baseColor = KTUtil.getCssVariableValue('--main-color');
				var secondaryColor = KTUtil.getCssVariableValue('--kt-gray-300');

				var options = {
					series: [{
						name: 'Đơn hàng',
						data: <?php echo $listJSONOrderDay; ?>
					}, ],
					chart: {
						fontFamily: 'inherit',
						type: 'bar',
						height: height,
						toolbar: {
							show: false
						},
						sparkline: {
							enabled: true
						}
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: ['100%'],
							borderRadius: 6
						}
					},
					legend: {
						show: false,
					},
					dataLabels: {
						enabled: false
					},
					stroke: {
						show: true,
						width: 9,
						colors: ['transparent']
					},
					xaxis: {
						axisBorder: {
							show: false,
						},
						axisTicks: {
							show: false,
							tickPlacement: 'between'
						},
						labels: {
							show: false,
							style: {
								colors: labelColor,
								fontSize: '12px'
							}
						},
						crosshairs: {
							show: false
						}
					},
					xaxis: {
						categories: ['02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', '00:00'],
					},
					yaxis: {
						labels: {
							show: false,
							style: {
								colors: labelColor,
								fontSize: '12px'
							}
						}
					},
					fill: {
						type: 'solid'
					},
					states: {
						normal: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						hover: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						active: {
							allowMultipleDataPointsSelection: false,
							filter: {
								type: 'none',
								value: 0
							}
						}
					},
					tooltip: {
						style: {
							fontSize: '12px'
						},
						x: {
							formatter: function(val) {
								return '' + val;
							}
						},
						y: {
							formatter: function(val) {
								return val
							}
						}
					},
					colors: [baseColor, secondaryColor],
					grid: {
						padding: {
							left: 10,
							right: 10
						},
						borderColor: borderColor,
						strokeDashArray: 4,
						yaxis: {
							lines: {
								show: true
							}
						}
					}
				};

				var chart = new ApexCharts(element, options);

				// Set timeout to properly get the parent elements width
				setTimeout(function() {
					chart.render();
				}, 300);
			}

			// Public methods
			return {
				init: function() {
					initChart();
				}
			}
		}();

		// Webpack support
		if (typeof module !== 'undefined') {
			module.exports = KTCardsWidget6;
		}

		// On document ready
		KTUtil.onDOMContentLoaded(function() {
			KTCardsWidget6.init();
		});
	</script>
	<script>
		"use strict";

		// Class definition
		var KTChartsWidget3 = function () {
			var chart = {
				self: null,
				rendered: false
			};

			// Private methods
			var initChart = function(chart) {
				var element = document.getElementById("kt_charts_widget_3");

				if (!element) {
					return;
				}
				
				var height = parseInt(KTUtil.css(element, 'height'));
				var labelColor = KTUtil.getCssVariableValue('--kt-gray-500');
				var lastColor = KTUtil.getCssVariableValue('--kt-gray-300');
				var borderColor = KTUtil.getCssVariableValue('--kt-border-dashed-color');
				var baseColor = KTUtil.getCssVariableValue('--main-color');
				var lightColor = KTUtil.getCssVariableValue('--kt-success');

				var options = {
					series: [{
						name: 'Tháng trước',
						data: <?php echo $listJSEarningPerDayLastMonth ?>
					},
					{
						name: 'Tháng nay',
						data: <?php echo $listJSEarningPerDay; ?>
					}],            
					chart: {
						fontFamily: 'inherit',
						type: 'area',
						height: height,
						toolbar: {
							show: false
						}
					},
					plotOptions: {

					},
					legend: {
						show: false
					},
					dataLabels: {
						enabled: false
					},
					fill: {
						type: "gradient",
						gradient: {
							shadeIntensity: 1,
							inverseColors: false,
							opacityFrom: 0.45,
							opacityTo: 0.05,
							stops: [20, 100, 100, 100]
						}
					},
					stroke: {
						curve: 'smooth',
						show: true,
						width: 3,
						colors: [lastColor, baseColor]
					},
					xaxis: {
						categories: <?php echo $listJSDayOfMonth; ?>,
						axisBorder: {
							show: false,
						},
						axisTicks: {
							show: false
						},
						tickAmount: 6,
						labels: {
							rotate: 0,
							rotateAlways: true,
							style: {
								colors: labelColor,
								fontSize: '12px'
							},
						},
						crosshairs: {
							position: 'front',
							stroke: {
								color: baseColor,
								width: 1,
								dashArray: 3
							}
						},
						tooltip: {  
							enabled: true,
							formatter: undefined,
							offsetY: 0,
							style: {
								fontSize: '12px'
							}
						}
					},
					yaxis: {
						// tickAmount: 5,
						// max: 50,
						// min: 0,
						labels: {
							style: {
								colors: labelColor,
								fontSize: '12px'
							},
							formatter: function (val) {
								return '₫' + val
							}
						}
					},
					states: {
						normal: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						hover: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						active: {
							allowMultipleDataPointsSelection: false,
							filter: {
								type: 'none',
								value: 0
							}
						}
					},
					tooltip: {
						style: {
							fontSize: '12px'
						},
						y: {
							formatter: function (val) {
								return new Intl.NumberFormat('vi-VN', {
									style: 'currency',
									currency: 'VND'
								}).format(val);
							}
						}
					},
					colors: [lastColor, baseColor],
					grid: {
						borderColor: borderColor,
						strokeDashArray: 4,
						yaxis: {
							lines: {
								show: true
							}
						}
					},
					markers: {
						color: [lastColor, baseColor],
						strokeColor: [lastColor, baseColor],
						strokeWidth: 3
					}
				};

				chart.self = new ApexCharts(element, options);

				// Set timeout to properly get the parent elements width
				setTimeout(function() {
					chart.self.render();
					chart.rendered = true;
				}, 200);  
			}

			// Public methods
			return {
				init: function () {
					initChart(chart);

					// Update chart on theme mode change
					KTThemeMode.on("kt.thememode.change", function() {                
						if (chart.rendered) {
							chart.self.destroy();
						}

						initChart(chart);
					});
				}   
			}
		}();

		// Webpack support
		if (typeof module !== 'undefined') {
			module.exports = KTChartsWidget3;
		}

		// On document ready
		KTUtil.onDOMContentLoaded(function() {
			KTChartsWidget3.init();
		});
	</script>

</body>

</html>