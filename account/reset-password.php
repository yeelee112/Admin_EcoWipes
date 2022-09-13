<?php 
    if (isset($_SESSION['adminName']) && isset($_SESSION['adminEmail'])) {
        header("Location: /");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
		<title>Quên mật khẩu | Admin EcoWipes E-commerce</title>
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
							<form class="form w-100" novalidate="novalidate" id="kt_password_reset_form" method="GET">
								<div class="text-center mb-10">
									<h1 class="text-dark fw-bolder mb-3">Quên mật khẩu?</h1>
									<div class="text-gray-500 fw-semibold fs-6">Nhập Email để đặt lại mật khẩu của bạn.</div>
								</div>
								<div class="fv-row mb-8">
									<input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent">
								</div>
								<div class="d-flex flex-wrap justify-content-center pb-lg-0">
									<button type="button" id="kt_password_reset_submit" class="btn btn-primary me-4">
										<span class="indicator-label">Gửi</span>
										<span class="indicator-progress">Chờ tẹo...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
									<a href="sign-in" class="btn btn-light">Hủy</a>
								</div>
							</form>
						</div>
					</div>
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
		<script>var hostUrl = "/metronic8/demo13/assets/";</script>
		<script src="../../../assets/plugins/global/plugins.bundle.js"></script>
		<script src="../../../assets/js/scripts.bundle.js"></script>
		<script src="../../../assets/js/custom/authentication/reset-password/reset-password.js"></script>
	</body>
</html>