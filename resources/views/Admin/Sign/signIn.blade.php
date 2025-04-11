<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
		<meta name="Author" content="Spruko Technologies Private Limited">
		<meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4"/>

		<!-- Title -->
		<title> Login </title>

		<!-- Favicon -->
		<link rel="icon" href="/images/logo-dgolf3.png" type="image/x-icon">

		<!-- Icons css -->
		<link href="Valex/html/assets/css/icons.css" rel="stylesheet">

		<!--  Right-sidemenu css -->
		<link href="Valex/html/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

		<!--  Custom Scroll bar-->
		<link href="Valex/html/assets/plugins/mscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet"/>

		<!--  Left-Sidebar css -->
		<link rel="stylesheet" href="Valex/html/assets/css/sidemenu1.css">

		<!--- Style css --->
		<link href="Valex/html/assets/css/style.css" rel="stylesheet">

		<!--- Dark-mode css --->
		<link href="Valex/html/assets/css/style-dark.css" rel="stylesheet">

		<!---Skinmodes css-->
		<link href="Valex/html/assets/css/skin-modes.css" rel="stylesheet" />

		<!--- Animations css-->
		<link href="Valex/html/assets/css/animate.css" rel="stylesheet">

	</head>
<body class="main-body bg-light">

    <!-- Loader -->
    <div id="global-loader">
        <img src="Valex/html/assets/img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

<!-- Page -->
<div class="page">
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
                <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
                    <div class="row wd-100p mx-auto text-center">
                        <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                            {{-- <img src="images/logo-dgolf3.png" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo"> --}}
                            <div class="h1">RUMP4T <i class="fa fa-cog fa-lg" aria-hidden="true"></i> ADMIN</div>
                        </div>
                    </div>
                </div>
                <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h5 class="font-weight-semibold mb-4" style="color: black">Login</h5>
                                            <form action="{{ route('login-web') }}" method="POST">
                                                @csrf
                                                @error('loginError')
                                                    <div class="alert alert-danger mb-0" role="alert">
                                                        <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                                                        <span class="alert-inner--text"><strong>{{ $message }}</strong></span>
                                                    </div>
                                                @enderror
                                                <br>
                                                @error('email')
                                                    <small style="color: red">{{ $message }}</small>
                                                @enderror
                                                <div class="form-group">
                                                <input class="form-control" value="{{ old('email') }}" name="email" placeholder="Enter your email" type="text">
                                                </div>
                                                @error('password')
                                                    <small style="color: red">{{ $message }}</small>
                                                @enderror
                                                <div class="form-group">
                                                    <input class="form-control" value="{{ old('password') }}" name="password" placeholder="Enter your password" type="password">
                                                </div>
                                                    <button type="submit" class="btn btn-outline-success ">Sign In</button>
                                            </form>
                                            {{-- <div class="main-signin-footer mt-5">
                                                <p><a href="">Forgot password?</a></p>
                                                <p>Don't have an account? <a href="page-signup.html">Create an Account</a></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>
</div>
<!-- End Page -->
		<!-- JQuery min js -->
		<script src="Valex/html/assets/plugins/jquery/jquery.min.js"></script>

		<!-- Bootstrap Bundle js -->
		<script src="Valex/html/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

		<!-- Ionicons js -->
		<script src="Valex/html/assets/plugins/ionicons/ionicons.js"></script>

		<!-- Moment js -->
		<script src="Valex/html/assets/plugins/moment/moment.js"></script>

		<!-- eva-icons js -->
		<script src="Valex/html/assets/js/eva-icons.min.js"></script>

		<!-- Rating js-->
		<script src="Valex/html/assets/plugins/rating/jquery.rating-stars.js"></script>
		<script src="Valex/html/assets/plugins/rating/jquery.barrating.js"></script>

		<!-- custom js -->
		<script src="Valex/html/assets/js/custom.js"></script>

	</body>
</html>