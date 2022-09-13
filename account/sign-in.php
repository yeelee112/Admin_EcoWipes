<?php 
    if (isset($_SESSION['adminName']) && isset($_SESSION['adminEmail'])) {
        header("Location: /");
    }
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Đăng nhập | Admin EcoWipes E-commerce</title>
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
							<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="/" method="POST">
								<div class="text-center mb-11">
									<h1 class="text-dark fw-bolder mb-3">Đăng nhập</h1>
									<!-- <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div> -->
								</div>
								<!-- <div class="row g-3 mb-9">
									<div class="col-md-6">
										<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
										<img alt="Logo" src="../../../assets/media/svg/brand-logos/google-icon.svg" class="h-15px me-3">Sign in with Google</a>
									</div>
									<div class="col-md-6">
										<a href="#" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
										<img alt="Logo" src="../../../assets/media/svg/brand-logos/apple-black.svg" class="theme-light-show h-15px me-3">
										<img alt="Logo" src="../../../assets/media/svg/brand-logos/apple-black-dark.svg" class="theme-dark-show h-15px me-3">Sign in with Apple</a>
									</div>
								</div> -->
								<!-- <div class="separator separator-content my-14">
									<span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
								</div> -->
								<div class="fv-row mb-8">
									<input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent">
								</div>
								<div class="fv-row mb-3">
									<input type="password" placeholder="Mật khẩu" name="password" autocomplete="off" class="form-control bg-transparent">
								</div>
								<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
									<div></div>
									<a href="reset-password" class="links-primary">Quên mật khẩu ?</a>
								</div>
								<div class="d-grid mb-10">
									<button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
										<span class="indicator-label">Đăng nhập</span>
										<span class="indicator-progress">Chờ một chút, đang xác thực... 
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
								</div>
								<!-- <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet? 
								<a href="sign-up.html" class="link-primary">Sign up</a></div> -->
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
						<!-- <img class="mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="" alt>
						<h1 class="text-white fs-2qx fw-bolder text-center mb-7">Fast, Efficient and Productive</h1>
						<div class="text-white fs-base text-center">In this kind of post, 
						<a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a>introduces a person they’ve interviewed 
						<br>and provides some background information about 
						<a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a>and their 
						<br>work following this is a transcript of the interview.</div> -->
					</div>
				</div>
			</div>
		</div>
		<script src="../../../assets/plugins/global/plugins.bundle.js"></script>
		<script src="../../../assets/js/scripts.bundle.js"></script>
		<script src="../../../assets/js/custom/authentication/sign-in/general.js"></script>
	</body>
</html>