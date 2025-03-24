<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>E-Voucher</title>
    <style>
        @media only screen and (max-width: 900px) {
            table.body h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table.body p,
            table.body ul,
            table.body ol,
            table.body td,
            table.body span,
            table.body a {
                font-size: 20px !important;
            }

            table.body .wrapper,
            table.body .article {
                padding: 10px !important;
            }

            table.body .content {
                padding: 0 !important;
            }

            table.body .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table.body .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table.body .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        @media all {
            .body {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                background-color: #f6f6f6;
                width: 100%;
            }

            .main {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                background: #ffffff;
                border-radius: 3px;
                width: 100%;
            }

            #gradient2 {
                background-image: linear-gradient(to right, #00A551, #140958);
                height: 10px;
            }

            #gradient3 {
                background-image: linear-gradient(to right, #00A551, #140958);
                height: 130px;
                padding: 35px 50px;
            }

            .content {
                box-sizing: border-box;
                display: block;
                margin: 0 auto;
                max-width: 880px;
                padding: 10px;
            }

            .dgolfbody {
                background-color: #f6f6f6;
                font-family: sans-serif;
                -webkit-font-smoothing: antialiased;
                font-size: 14px;
                line-height: 1.9;
                margin: 0;
                padding: 0;
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }

            .container {
                font-family: sans-serif;
                font-size: 14px;
                vertical-align: top;
                display: block;
                max-width: 880px;
                padding: 10px;
                width: 880px;
                margin: 0 auto;
            }

            .driving {
                font-family: sans-serif;
                font-size: 14px;
                vertical-align: top;
            }

            .logodgolf {
                padding: 30px 50px;
            }

            .wrapper {
                font-family: sans-serif;
                font-size: 14px;
                vertical-align: top;
                box-sizing: border-box;
                padding: 20px 50px;
            }

            .dgolf-wrapper {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                width: 100%;
            }

            .wrap-td {
                font-family: sans-serif;
                font-size: 14px;
                vertical-align: top;
            }

            .welcome-msg {
                font-family: sans-serif;
                font-size: 24px;
                font-weight: bold;
                margin: 0;
                margin-bottom: 15px;
            }

            .normal-teks {
                font-family: sans-serif;
                font-size: 14px;
                font-weight: normal;
                margin: 0;
                margin-bottom: 25px;
            }

            .cs-email {
                color: white;
                font-size: 18px;
                font-weight: bold;
                line-height: 20px;
            }

            .footer-cs {
                color: white;
                font-size: 13px;
            }

            .otp-number {
                font-family: sans-serif;
                font-size: 35px;
                font-weight: s bold;
                margin-top: 10px;
                margin-bottom: 35px;
                border-radius: 20px;
                background-color: #00A551;
                width: 200px;
                text-align: center;
                color: white;
            }

            .valign-top {
                vertical-align: top;
            }

            .valign-center {
                vertical-align: center;
            }

            th,
            td {
                padding: 2px;
            }

            .section-title {
                background-color: #C6EAD9;
                padding: 10px;
                margin: 0px 1px 0px 1px;
                border-bottom: 1px solid #dddd
            }

            .section-title2 {
                background-color: #99C89C;
                padding: 10px 0px 10px 20px;
                margin: 0px 10px 0px 10px;
                border: 1px solid #dddd;
            }

            .welcome-msg2 {
                font-family: sans-serif;
                font-size: 18px;
                font-weight: bold;
                margin: 0;
            }

            .wrap-td2 {
                font-family: sans-serif;
                font-size: 16px;
                vertical-align: top;
                padding: 10px 20px;
            }

            .normal-teks2 {
                font-family: sans-serif;
                font-size: 16px;
                font-weight: normal;
                margin: 0;
            }

            .wrapper2 {
                font-family: sans-serif;
                font-size: 16px;
                vertical-align: top;
                box-sizing: border-box;
                padding: 20px 50px;
            }

            .wrapper3 {
                font-family: sans-serif;
                font-size: 16px;
                vertical-align: top;
                box-sizing: border-box;
                padding: 0px 50px 20px 50px;
            }

            .dgolf-wrapper2 {
                border-collapse: separate;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                width: 97.5%;
                border: 1px solid #dddd;
                margin-top: 10px;
                margin-left: 10px;
            }

            .no-table1 {
                width: 50px;
                background-color: #C6EAD9;
                padding: 10px;
                text-align: center;
                font-weight: bold;
                border-bottom: 1px solid #dddd;
                border-right: 1px solid #dddd;
            }

            .item-table1 {
                width: 450px;
                background-color: #C6EAD9;
                padding: 10px;
                text-align: center;
                font-weight: bold;
                border-bottom: 1px solid #dddd;
                border-right: 1px solid #dddd;
            }

            .amount-table1 {
                width: 200px;
                background-color: #C6EAD9;
                padding: 10px;
                text-align: center;
                font-weight: bold;
                border-bottom: 1px solid #dddd;
            }

            .ereceipt-note {
                color: #ffffff;
                text-align: right;
                background-color: #00A551;
                padding: 5px 10px;
                font-size: 15px;
                border-radius: 10px;
                border: 1px solid #00A551
            }
        }
    </style>
</head>

<body class="dgolfbody">
    <table class="body" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%"
        bgcolor="#f6f6f6">
        <tr>
            <td class="driving" valign="top"></td>
            <td class="container" width="680" valign="top">
                <div class="content">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" class="main" width="100%">
                        <tr>
                            <th id="gradient2" colspan="2"></th>
                        </tr>

                        <th style="text-align:left; width:50%;" class="logodgolf"><img
                                src="http://dgolf-be.aksiteknologi.com/images/logo-dgolf3.png" />
                        </th>
                        <th style="text-align:right;padding-right:65px;"><span
                                class="ereceipt-note">E-VOUCHER</span><br>Transaction No: <span
                                style="text-align:right;"> {{ $datasEmail->voucher }} </span></th>


                    </table>
                    <table role="presentation" class="main" width="100%">

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper3" valign="top">
                                <table
                                    style="width:97.5%; border-spacing: 10px; border:1px solid #dddd; margin-left:10px; margin-bottom:30px;"
                                    class="table">

                                    <tr>
                                        <td class="valign-top">

                                            <table class="table table-detail">
                                                <tr>
                                                    <td style="padding:0px 20px;">Name</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:20px;color:#7C7C7C;padding:0px 20px;">
                                                        {{ $datasEmail->user->gender == 'L' ? 'Mr.' : 'Mrs.' }}
                                                        {{ $datasEmail->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px 20px 0px;">Booking Date</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:20px;color:#7C7C7C;padding:0px 20px;">
                                                        {{ $datasEmail->created_at->format('M d Y') }} </td>
                                                </tr>

                                            </table>
                                        </td>
                                        {{-- <td class="valign-center">
                                            <table class="table table-detail">
                                                <tr>
                                                    <td style="padding:0px 20px;padding-left:80px;">Booking ID</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:35px;color:#00A551;padding-left:80px;"> -
                                                    </td>
                                                </tr>
                                            </table>
                                        </td> --}}
                                    </tr>
                                </table>


                                <table style="width:100%;" class="table">
                                    <h3 class="section-title2">Information</h3>
                                </table>
                                <table class="dgolf-wrapper2" role="presentation" border="0" cellpadding="0"
                                    cellspacing="0" width="100%">
                                    <tr>
                                        <td class="wrap-td2" valign="top">
                                            <p class="welcome-msg2"> {{ $datasEmail->event->title }} </p>
                                            <p style="padding-bottom:10px;" class="normal-teks2"><i>
                                                    {{ $datasEmail->event->eventCommonity->title }} </i></p>
                                            <p style="padding-bottom:10px;" class="normal-teks2">
                                                {{ $datasEmail->event->type_scor }} -
                                                {{ $datasEmail->event->play_date_start }} </p>
                                            <p class="normal-teks2"><b> {{ $datasEmail->event->golfCourseEvent->name }}
                                                </b></p>
                                            <p class="normal-teks2">{{ $datasEmail->event->golfCourseEvent->address }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>


                                <table style="width:100%; border-spacing: 10px;" class="table">

                                    <h3 style="margin-top:10px;" class="section-title2">Guest(s)</h3>

                                    <tr>
                                        <td class="valign-top">

                                            <table class="table table-detail">
                                                <tr>
                                                    <td style="" class="no-table1">No</td>
                                                    <td style="" class="item-table1">Guest Name </td>
                                                    <td style="" class="amount-table1">No. Voucher</td>
                                                    <td style="" class="amount-table1">Amount</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px; text-align:center; border-bottom:1px solid #dddd;"
                                                        class="no-table2">1</td>
                                                    <td
                                                        style="padding:10px; text-align:left;border-bottom:1px solid #dddd;">
                                                        {{ $datasEmail->user->name }} </td>
                                                    <td
                                                        style="padding:10px; text-align:center;font-weight:bold;border-bottom:1px solid #dddd;">
                                                        {{ $datasEmail->voucher }} </td>
                                                    <td
                                                        style="padding:10px; text-align:center;font-weight:bold;border-bottom:1px solid #dddd;">
                                                        Rp. {{ $datasEmail->event->price }} </td>
                                                </tr>

                                            </table>


                                    </tr>
                                </table>

                                <table class="dgolf-wrapper2" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td class="wrap-td2" valign="top">
                                            <p style="padding-bottom:10px;" class="normal-teks2">Segera lakukan pembayaran pada rekening berikut : </p>
                                            <p style="padding-bottom:10px;" class="normal-teks2">Nama Bank : {{ $datasEmail->event->nama_bank }}</p>
                                            <p style="padding-bottom:10px;" class="normal-teks2">Atas Nama : {{ $datasEmail->event->nama_rekening }}</p>
                                            <p style="padding-bottom:10px;" class="normal-teks2">No Rekening : {{ $datasEmail->event->no_rekening }}</p>
                                        </td>
                                    </tr>
                                </table>

                        <tr>
                            <td id="gradient3">
                                <div width="100%">
                                    <div style="width:250px; float:left;"><a><img
                                                src="http://dgolf-be.aksiteknologi.com/images/logo-dgolf4.png"></a>
                                    </div>
                                    <div style="float:right; margin-top:30px;">
                                        <p class="footer-cs">Customer Service Email: </br><span
                                                class="cs-email">cs@dgolf.com</span></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
            </td>
        </tr>

        <!-- END MAIN CONTENT AREA -->
    </table>
    <!-- END CENTERED WHITE CONTAINER -->
    </div>
    </td>
    <td style="font-family: sans-serif; font-size: 20px; vertical-align: top;" valign="top">&nbsp;</td>
    </tr>
    </table>
</body>

</html>
