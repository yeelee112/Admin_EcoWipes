<?php
    $checkSecure = 0;
    if ($_GET["id"]) {
        $idOrder = $_GET["id"];
        $checkSecure++;
    }

    require_once '../../../DataProvider.php';
    require_once "../../../functions.php";
    $totalCartPrice = 0;
    $shippingFee = 0;

    $sqlOrder = "select * from order_detail where id = '$idOrder'";
    $listOrder = DataProvider::execQuery($sqlOrder);
    $rowOrder = mysqli_fetch_assoc($listOrder);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Chỉnh sửa đơn hàng | Admin EcoWipes E-commerce</title>
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
									<h1 class="d-flex align-items-center text-dark fw-bold my-1 fs-3">Chỉnh sửa</h1>
									<span class="h-20px border-gray-200 border-start mx-4"></span>
									<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
										<li class="breadcrumb-item text-muted">
											<a href="../../../" class="text-muted text-hover-primary">Trang chủ</a>
										</li>
										<li class="breadcrumb-item">
											<span class="bullet bg-gray-200 w-5px h-2px"></span>
										</li>
										<li class="breadcrumb-item text-muted">eCommerce</li>
										<li class="breadcrumb-item">
											<span class="bullet bg-gray-200 w-5px h-2px"></span>
										</li>
										<li class="breadcrumb-item text-muted">Đơn hàng</li>
										<li class="breadcrumb-item">
											<span class="bullet bg-gray-200 w-5px h-2px"></span>
										</li>
										<li class="breadcrumb-item text-dark">Chỉnh sửa đơn hàng <?php echo $rowOrder["id"]; ?></li>
									</ul>
								</div>
								<div class="d-flex align-items-center py-1">
                                	Hallo buổi sáng tốt nành
                            	</div>
							</div>
						</div>
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<div id="kt_content_container" class="container-xxl">
								<form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="order" method="POST">
									<div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
										<div class="card card-flush py-4">
											<div class="card-header">
												<div class="card-title">
													<h2>Chi tiết đơn hàng</h2>
												</div>
											</div>
											<div class="card-body pt-0">
												<div class="d-flex flex-column gap-10">
													<div class="fv-row">
														<label class="form-label">Mã đơn hàng</label>
														<div class="fw-bold fs-3">#<?php echo $rowOrder["id"]; ?></div>
														<input name="id_order" type="hidden" value="<?php echo $rowOrder["id"]; ?>">
													</div>
													<div class="fv-row">
														<label class="required form-label">Trạng thái</label>
														<select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" name="status_order" id="kt_ecommerce_edit_order_shipping">
															<option></option>
															<option value="0" <?php if($rowOrder["status_order"] == 0){ echo 'selected="selected"'; } ?>>Chờ xác nhận</option>
															<option value="1" <?php if($rowOrder["status_order"] == 1){ echo 'selected="selected"'; } ?>>Đã xác nhận</option>
															<option value="2" <?php if($rowOrder["status_order"] == 2){ echo 'selected="selected"'; } ?>>Đã hoàn thành</option>
															<option value="3" <?php if($rowOrder["status_order"] == 3){ echo 'selected="selected"'; } ?>>Đã hủy</option>
														</select>
														<div class="text-muted fs-7">Chọn phương thức vận chuyển.</div>
													</div>
													<div class="fv-row">
														<label class="required form-label">Phương thức thanh toán</label>
														<select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" name="payment_method" id="kt_ecommerce_edit_order_payment">
															<option></option>
															<option value="COD" <?php if($rowOrder["payment_method"] == "COD"){ echo 'selected="selected"'; } ?>>Cash on Delivery (COD)</option>
															<option value="Banking" <?php if($rowOrder["payment_method"] == "Banking"){ echo 'selected="selected"'; } ?>>Chuyển khoản</option>
															<option value="Momo">Thanh toán trực tuyến (Momo)</option>
															<!-- <option value="paypal">Paypal</option> -->
														</select>
														<div class="text-muted fs-7">Chọn phương thức thanh toán.</div>
													</div>
													<div class="fv-row">
														<label class="required form-label">Ngày đặt đơn</label>
														<input id="kt_ecommerce_edit_order_date" name="order_date" placeholder="Select a date" disabled class="form-control mb-2" value="<?php echo date("Y-m-d H:i:s", strtotime($rowOrder["created_at"])) ?>">
														<!-- <div class="text-muted fs-7">Set the date of the order to process.</div> -->
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
										<div class="card card-flush py-4">
											<div class="card-header">
												<div class="card-title">
													<h2>Sản phẩm đã đặt</h2>
												</div>
											</div>
											<div class="card-body pt-0">
												<div class="d-flex flex-column gap-10">
													<div>
														<div class="row row-cols-1 row-cols-xl-2 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll" id="kt_ecommerce_edit_order_selected_products">
														<?php 
															$sql = "select * from order_detail od join order_items ot on od.id = ot.order_id, product p, image_product i where ot.product_id = p.id and i.product_id = p.id and od.id = '$idOrder'";
															$list = DataProvider::execQuery($sql);
															while ($row = mysqli_fetch_array($list, MYSQLI_ASSOC)) {
																$totalPrice = $row["price"] * $row["quantity"];
																$totalCartPrice += $totalPrice;
																$shippingFee = $row["shipping_fee"];
														?>
															<div class="col my-2">
																<div class="d-flex align-items-center border border-dashed p-3 rounded">
																	<a href="#" class="symbol symbol-50px">
																		<span class="symbol-label" style="background-image:url(<?php echo $row["img_thumb"] ?>);"></span>
																	</a>
																	<div class="ms-5">
																		<div class="text-gray-800 text-hover-primary fs-5 fw-bold"><?php echo $row["product_name"] ?><span class="small text-muted"> x <?php echo $row["quantity"] ?></span></div>
																		<div class="fw-semibold fs-7">Giá:
																		<span data-kt-ecommerce-edit-order-filter="price"><?php echo number_format($totalPrice, 0, ",", ".") ?> đ</span><span class="small text-muted"> (<?php echo number_format($row["price"], 0, ",", ".") ?> đ)</span></div>
																		<div class="text-muted fs-7">ID: <?php echo $row["id"] ?></div>
																	</div>
																</div>
															</div>
														<?php } ?>
														</div>
														<div class="fw-bold fs-4">Tổng:
														<span id="kt_ecommerce_edit_order_total_price"><?php echo number_format($totalCartPrice, 0, ",", ".") ?> đ</span><span class="small text-muted money-sign-small"> (Phí ship: <?php echo number_format($shippingFee, 0, ",", ".") ?> đ)</span></div>
													</div>
												</div>
											</div>
										</div>
										<div class="card card-flush py-4">
											<div class="card-header">
												<div class="card-title">
													<h2>Thông tin giao hàng</h2>
												</div>
											</div>
											<div class="card-body pt-0">
												<div class="d-flex flex-column gap-5 gap-md-7">
													<div class="d-flex flex-column gap-5 gap-md-7" id="kt_ecommerce_edit_order_shipping_form">
														<div class="fs-3 fw-bold mb-n2">Địa chỉ giao hàng</div>
														<div class="d-flex flex-column flex-md-row gap-5">
															<div class="fv-row flex-row-fluid">
																<label class="form-label">Họ tên</label>
																<input class="form-control" name="fullname" placeholder="Họ tên" value="<?php echo $rowOrder["fullname"] ?>">
															</div>
															<div class="flex-row-fluid">
																<label class="form-label">Số điện thoại</label>
																<input class="form-control" name="phone" placeholder="Số điện thoại" value="<?php echo $rowOrder["phone"] ?>">
															</div>
														</div>
														<div class="d-flex flex-column flex-md-row gap-5">
															<div class="flex-row-fluid">
																<label class="form-label">Email</label>
																<input class="form-control" name="email" placeholder value="<?php echo $rowOrder["email"] ?>">
															</div>
															<div class="fv-row flex-row-fluid">
																<label class="form-label">Địa chỉ</label>
																<input class="form-control" name="address_detail" placeholder="Địa chỉ" value="<?php echo $rowOrder["address_detail"] ?>">
															</div>
														</div>
														<div class="fv-row">
															<label class="form-label">Xã/Phường - Huyện/Quận - Tỉnh/Thành</label>
															<input class="form-control" name="address" placeholder="Xã/Phường - Huyện/Quận - Tỉnh/Thành" value="<?php echo $rowOrder["address"] ?>">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="d-flex justify-content-end">
											<a href="order" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Hủy</a>
											<button type="submit" id="kt_ecommerce_edit_order_submit" class="btn btn-primary">
												<span class="indicator-label">Lưu</span>
												<span class="indicator-progress">Đợi xíu nhen... 
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											</button>
										</div>
									</div>
								</form>
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
		<script src="../../../assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
		<script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
		<script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
		<script src="../../../assets/js/custom/apps/ecommerce/sales/save-order.js"></script>
		<script src="../../../assets/js/widgets.bundle.js"></script>
		<script src="../../../assets/js/custom/widgets.js"></script>
		<script src="../../../assets/js/custom/apps/chat/chat.js"></script>
		<script src="../../../assets/js/custom/intro.js"></script>
		<script src="../../../assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="../../../assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="../../../assets/js/custom/utilities/modals/users-search.js"></script>
	</body>
</html>