<?php require_once "../../../functions.php"; ?>
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
	<title>Đơn hàng | Admin EcoWipes E-commerce</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:locale" content="en_US">
	<meta property="og:type" content="article">
	<link rel="shortcut icon" href="../../../assets/media/logos/favicon.ico">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
	<link href="../../../assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css">
	<link href="../../../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">
	<link href="../../../assets/css/style.bundle.css" rel="stylesheet" type="text/css">
</head>

<body data-kt-name="metronic" id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
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
			<?php require_once "../../../left-header.php" ?>
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<?php require_once "../../../top-header.php" ?>
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<div class="toolbar" id="kt_toolbar">
						<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
							<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
								<h1 class="d-flex align-items-center text-dark fw-bold my-1 fs-3">Danh sách đơn hàng</h1>
							</div>
							<div class="d-flex align-items-center py-1">
								Hallo buổi sáng tốt nành
							</div>
						</div>
					</div>
					<div class="post d-flex flex-column-fluid" id="kt_post">
						<div id="kt_content_container" class="container-xxl">
							<div class="card card-flush">
								<div class="card-header align-items-center py-5 gap-2 gap-md-5">
									<div class="card-title">
										<div class="d-flex align-items-center position-relative my-1">
											<span class="svg-icon svg-icon-1 position-absolute ms-4">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
													<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
												</svg>
											</span>
											<input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Tìm kiếm">
										</div>
									</div>
									<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
										<div class="input-group w-250px">
											<input class="form-control form-control-solid rounded rounded-end-0" placeholder="Chọn mốc thời gian" id="kt_ecommerce_sales_flatpickr">
											<button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
												<span class="svg-icon svg-icon-2">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
														<rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
													</svg>
												</span>
											</button>
										</div>
										<div class="w-200px mw-auto">
											<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Trạng thái" data-kt-ecommerce-order-filter="status">
												<option></option>
												<option value="all">Tất cả</option>
												<option value="Đã hoàn thành">Đã hoàn thành</option>
												<option value="Đã xác nhận">Đã xác nhận</option>
												<option value="Đã Từ chối">Đã từ chối</option>
												<option value="Chờ xác nhận">Chờ xác nhận</option>
											</select>
										</div>
										<!-- <a href="../catalog/add-product.html" class="btn btn-primary">Add Order</a> -->
									</div>
								</div>
								<div class="card-body pt-0">
									<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
										<thead>
											<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
												<th class="w-10px pe-2">
													<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
														<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1">
													</div>
												</th>
												<th class="min-w-100px">Mã đơn hàng</th>
												<th class="min-w-175px">Khách hàng</th>
												<th class="text-end min-w-70px">Trạng thái</th>
												<th class="text-end min-w-100px">Tỉnh/thành</th>
												<th class="text-end min-w-100px">Tổng tiền</th>
												<th class="text-end min-w-100px">Thời gian</th>
												<th class="text-end min-w-100px">#</th>
											</tr>
										</thead>
										<tbody class="fw-semibold text-gray-600">
											<?php
											require_once '../../../DataProvider.php';
											require_once "../../../functions.php";


											$sqlOrder = "select * from order_detail od order by created_at DESC";
											$listOrder = DataProvider::execQuery($sqlOrder);
											while ($rowOrder = mysqli_fetch_array($listOrder, MYSQLI_ASSOC)) {
											?>
												<tr>
													<td>
														<div class="form-check form-check-sm form-check-custom form-check-solid">
															<input class="form-check-input" type="checkbox" value="1">
														</div>
													</td>
													<td data-kt-ecommerce-order-filter="order_id">
														<a href="order-detail?id=<?php echo $rowOrder["id"] ?>" class="text-gray-800 text-hover-primary fw-bold"><?php echo $rowOrder["id"] ?></a>
													</td>
													<td>
														<div class="d-flex align-items-center">
															<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
																<a href="#">
																	<img class="avt-account" src="https://ui-avatars.com/api/?name=<?php echo $rowOrder["fullname"] ?>&rounded=true&size=48&background=random&color=ffffff">
																</a>
															</div>
															<div class="ms-5">
																<a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold"><?php echo $rowOrder["fullname"] ?></a>
															</div>
														</div>
													</td>
													<?php
													if ($rowOrder["status_order"] == 1) {
														echo '
                                                            <td class="text-end pe-0" data-order="Đã xác nhận">
                                                                <div class="badge badge-light-primary">Đã xác nhận</div>
                                                            </td>';
													}
													else if ($rowOrder["status_order"] == 2) {
														echo '
                                                            <td class="text-end pe-0" data-order="Đang giao hàng">
                                                                <div class="badge badge-light-info">Đang giao hàng</div>
                                                            </td>';
													}
													else if ($rowOrder["status_order"] == 3) {
														echo '
                                                            <td class="text-end pe-0" data-order="Đã hoàn thành">
                                                                <div class="badge badge-light-success">Đã hoàn thành</div>
                                                            </td>';
													}
													else if ($rowOrder["status_order"] == 4) {
														echo '
                                                            <td class="text-end pe-0" data-order="Đã hủy">
                                                                <div class="badge badge-light-danger">Đã hủy</div>
                                                            </td>';
													} else {
														echo '
                                                            <td class="text-end pe-0" data-order="Chờ xác nhận">
                                                                <div class="badge badge-light-warning">Chờ xác nhận</div>
                                                            </td>';
													}
													?>
													<td class="text-end pe-0">
														<span class="fw-bold"><?php echo after_last('-', $rowOrder["address"]); ?></span>
													</td>
													<td class="text-end">
														<span class="fw-bold"><?php echo number_format($rowOrder["total_price"] + $rowOrder["shipping_fee"], 0, ".", ",") ?> đ</span>
													</td>
													<td class="text-end" data-order="<?php echo date("Y-m-d", strtotime($rowOrder["created_at"])) ?>">
														<span class="fw-bold"><?php echo date("d/m/Y H:i:s ", strtotime($rowOrder["created_at"])) ?></span>
													</td>
													<td class="text-end">
														<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Chọn
															<span class="svg-icon svg-icon-5 m-0">
																<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
																</svg>
															</span>
														</a>
														<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
															<div class="menu-item px-3">
																<a href="order-detail?id=<?php echo $rowOrder["id"] ?>" class="menu-link px-3">Xem</a>
															</div>
															<div class="menu-item px-3">
																<a href="edit-order?id=<?php echo $rowOrder["id"] ?>" class="menu-link px-3">Chỉnh sửa</a>
															</div>
															<div class="menu-item px-3">
																<a href="#" class="menu-link px-3" data-kt-ecommerce-order-filter="delete_row">Xóa</a>
															</div>
														</div>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
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
	<script src="../../../assets/plugins/global/plugins.bundle.js"></script>
	<script src="../../../assets/js/scripts.bundle.js"></script>
	<script src="../../../assets/plugins/custom/datatables/datatables.bundle.js"></script>
	<script src="../../../assets/js/custom/apps/ecommerce/sales/listing.js"></script>
	<script src="../../../assets/js/widgets.bundle.js"></script>
	<script src="../../../assets/js/custom/widgets.js"></script>
	<script src="../../../assets/js/custom/apps/chat/chat.js"></script>
	<script src="../../../assets/js/custom/intro.js"></script>
	<script src="../../../assets/js/custom/utilities/modals/upgrade-plan.js"></script>
	<script src="../../../assets/js/custom/utilities/modals/create-app.js"></script>
	<script src="../../../assets/js/custom/utilities/modals/users-search.js"></script>
</body>
<!--end::Body-->

</html>