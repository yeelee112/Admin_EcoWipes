<?php 
    if (isset($_SESSION['adminName']) && isset($_SESSION['adminEmail'])) {
        header("Location: /");
    }
    require_once '../DataProvider.php';

    $checkSecure = 0;
    $mysqli = DataProvider::getConnection();

    if(isset($_GET['email'])){
        $email = mysqli_real_escape_string($mysqli, $_GET["email"]);
        $checkSecure++;
    }

    if(isset($_GET['token'])){
        $token = mysqli_real_escape_string($mysqli, $_GET["token"]);
        $checkSecure++;
    }

    $sql = "select * from account_admin where admin_email = '$email' and token = '$token'";
    $list = DataProvider::execQuery($sql);
    $numRow = mysqli_num_rows($list);

    if($checkSecure < 2 || $numRow == 0){
        header("Location: sign-in");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
		<title>Đặt mật khẩu mới | Admin EcoWipes E-commerce</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta property="og:locale" content="en_US">
		<meta property="og:type" content="article">
		<link rel="shortcut icon" href="../../../assets/media/logos/favicon.ico">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
		<link href="../../../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">
		<link href="../../../assets/css/style.bundle.css" rel="stylesheet" type="text/css">
	</head>
	<body data-kt-name="metronic" id="kt_body" class="app-blank">
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
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<div class="w-lg-500px p-10">
							<form class="form w-100" novalidate="novalidate" id="kt_new_password_form" data-kt-redirect-url="sign-in" method="POST">
								<div class="text-center mb-10">
									<h1 class="text-dark fw-bolder mb-3">Cài đặt mật khẩu mới</h1>
									<div class="text-gray-500 fw-semibold fs-6">Bạn đã đổi mật khẩu rồi? 
									<a href="sign-in.html" class="link-primary fw-bold">Đăng nhập</a></div>
								</div>
                                <input type="hidden" name="email" value="<?php echo $email ?>">
								<input type="hidden" name="token" value="<?php echo $token ?>">
								<div class="fv-row mb-8" data-kt-password-meter="true">
									<div class="mb-1">
										<div class="position-relative mb-3">
											
											<input class="form-control bg-transparent" type="password" placeholder="Mật khẩu mới" name="password" autocomplete="off">
											<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
												<i class="bi bi-eye-slash fs-2"></i>
												<i class="bi bi-eye fs-2 d-none"></i>
											</span>
										</div>
										<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
										</div>
									</div>
									<div class="text-muted">Sử dụng 8 ký tự trở lên với sự kết hợp của các chữ cái, số và ký tự đặc biệt.</div>
								</div>
								<div class="fv-row mb-8">
									<input type="password" placeholder="Nhập lại mật khẩu mới" name="confirm-password" autocomplete="off" class="form-control bg-transparent">
								</div>
								<!-- <div class="fv-row mb-8">
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="toc" value="1">
										<span class="form-check-label fw-semibold text-gray-700 fs-6 ms-1">I Agree &amp; 
										<a href="#" class="ms-1 link-primary">Terms and conditions</a>.</span>
									</label>
								</div> -->
								<div class="d-grid mb-10">
									<button type="button" id="kt_new_password_submit" class="btn btn-primary">
										<span class="indicator-label">Cập nhật</span>
										<span class="indicator-progress">Chờ xíu... 
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
								</div>
							</form>
						</div>
					</div>
					<!-- <div class="d-flex flex-center flex-wrap px-5">
						<div class="d-flex fw-semibold text-primary fs-base">
							<a href="../../../pages/team.html" class="px-5" target="_blank">Terms</a>
							<a href="../../../pages/pricing/column.html" class="px-5" target="_blank">Plans</a>
							<a href="../../../pages/contact.html" class="px-5" target="_blank">Contact Us</a>
						</div>
					</div> -->
				</div>
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url(https://lh3.googleusercontent.com/pw/AL9nZEU5qBhwFM5RP9nj5st9fAiaJh9IjEaPTStJOeLqKeaLPt0r794XgBmIHNQps_Z4NiG1NYuLUWGmQRyNtd5bb7ODdwIN57OSWPWABsZkSweudERclWXKNXbxyOXwB1hDm6CpTE4nQhTh3rP0sHmX7KRJ=w2040-h1148-no)">
					<div class="d-flex flex-column flex-center py-15 px-5 px-md-15 w-100">
						<a href="../../../" class="mb-12">
							<img alt="Logo" src="../../../assets/media/logos/logo_full.svg" class="h-200px">
						</a>
					</div>
				</div>
			</div>
		</div>
		<script src="../../../assets/plugins/global/plugins.bundle.js"></script>
		<script src="../../../assets/js/scripts.bundle.js"></script>
		<script src="../../../assets/js/custom/authentication//new-password/new-password.js"></script>
	</body>
</html>