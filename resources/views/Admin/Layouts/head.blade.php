<!DOCTYPE html>
<html>
<head>
<!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4"/>
<!-- Title -->
<title>RUMP4T</title>
<link rel="icon" href="/images/logo-dgolf4.png" type="image/x-icon">
<link href="/Valex/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
<link href="/Valex/assets/plugins/iconfonts/icons.css" rel="stylesheet" />
<!--- Internal Fontawesome css-->
<link href="/Valex/html/assets/plugins/fontawesome-free/css/all.min.css" rel="stylesheet">
<!---Ionicons css-->
<link href="/Valex/html/assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
<!---Internal Typicons css-->
<link href="/Valex/html/assets/plugins/typicons.font/typicons.css" rel="stylesheet">
<!---Internal Feather css-->
<link href="/Valex/html/assets/plugins/feather/feather.css" rel="stylesheet">
<!---Internal Falg-icons css-->
<link href="/Valex/html/assets/plugins/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
<!-- Style css -->
<link href="/Valex/html/assets/css/style.css" rel="stylesheet">

<link href="/Valex/assets/css/themes/all-themes.css" rel="stylesheet" />
<!-- Icons css -->
<link href="/Valex/html/assets/css/icons.css" rel="stylesheet">
<!--  Custom Scroll bar-->
<link href="/Valex/html/assets/plugins/mscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet"/>
<!--  Left-Sidebar css -->
<link rel="stylesheet" href="/Valex/html/assets/css/sidemenu1.css">
<!--- Style css --->
<link href="/Valex/html/assets/css/style.css" rel="stylesheet">
<!--- Animations css-->
<link href="/Valex/html/assets/css/animate.css" rel="stylesheet">
<!--  Owl-carousel css-->
<link href="/Valex/html/assets/plugins/owl-carousel/owl.carousel.css" rel="stylesheet" />
<!--  Right-sidemenu css -->
<link href="/Valex/html/assets/plugins/sidebar/sidebar.css" rel="stylesheet">
<!-- Sidemenu css -->
<link rel="stylesheet" href="/Valex/html/assets/css/sidemenu1.css">
<!-- Maps css -->
<link href="/Valex/html/assets/plugins/jqvmap/jqvmap.min.css" rel="stylesheet">
<!-- style css -->
<link href="/Valex/html/assets/css/style.css" rel="stylesheet">
<!-- Internal Select2 css -->
<link href="/Valex/html/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<!--Internal Sumoselect css-->
<link rel="stylesheet" href="/Valex/html/assets/plugins/sumoselect/sumoselect.css">
<!--Internal  Datetimepicker-slider css -->
<link href="/Valex/html/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css" rel="stylesheet">
<link href="/Valex/html/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet">
<link href="/Valex/html/assets/plugins/pickerjs/picker.min.css" rel="stylesheet">
<!-- Internal Spectrum-colorpicker css -->
<link href="/Valex/html/assets/plugins/spectrum-colorpicker/spectrum.css" rel="stylesheet">
<!--Internal  Quill css -->
<link href="/Valex/html/assets/plugins/quill/quill.snow.css" rel="stylesheet">
<link href="/Valex/html/assets/plugins/quill/quill.bubble.css" rel="stylesheet">
<link href="/Valex/html/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet">
<link href="/Valex/html/assets/plugins/pickerjs/picker.min.css" rel="stylesheet">

<!--- Style css --->
<link href="/Valex/html/assets/css/style.css" rel="stylesheet">
<!-- text editor basecap trix -->
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<style>
    .riwayat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        padding: 6px 10px;
        border-radius: 6px;
        transition: background 0.15s;
    }
    .riwayat-item:hover {
        background:rgb(199, 199, 199);
        cursor: pointer;
    }

    .riwayat-text {
        flex: 1 1 auto;
        padding-right: 10px;
    }

    .riwayat-btn {
        flex-shrink: 0;
    }
    .ui-datepicker {
        z-index: 99999 !important;
        position: relative;
    }

    .ui-datepicker select.ui-datepicker-year {
        width: 90px !important;
        min-width: 60px !important;
        font-size: 1em;
    }

    /* Hide days grid and month selector */
    .hide-calendar .ui-datepicker-calendar,
    .hide-calendar .ui-datepicker-month {
        display: none;
    }

    /* Optional: Hide previous/next arrows */
    .hide-calendar .ui-datepicker-prev,
    .hide-calendar .ui-datepicker-next {
        display: none;
    }

    /* Custom CSS for switch button */
    .custom-switch {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 20px;
    }

    .custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .custom-switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 24px;
    }

    .custom-switch-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .custom-switch-slider {
        background-color: #007bff;
    }

    input:checked + .custom-switch-slider:before {
        transform: translateX(22px);
    }

    .form-preview {
      border: 1px solid #ccc;
      padding: 15px;
      margin-top: 20px;
    }

    .custom-multiselect {
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 5px;
    position: relative;
    cursor: pointer;
}
.custom-multiselect .selected-options {
    min-height: 20px;
}
.custom-multiselect .options {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 10;
    background: white;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    padding: 8px;
}
.custom-multiselect.open .options {
    display: block;
}
</style>

</head>

<body class="main-body app sidebar-mini">

    <!-- Loader -->

    <!-- /Loader -->
<!-- Page -->
<div class="page">
