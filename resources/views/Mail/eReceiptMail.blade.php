<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>E-Receipt</title>
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
                font-ssize: 14px;
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
                padding: 10px 20px;
                font-size: 30px;
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

                        <th style="text-align:left; width:50%;" class="logodgolf"><img src="http://dgolf-be.aksiteknologi.com/images/logo-dgolf3.png" />
                        </th>
                        <th style="text-align:right;padding-right:65px;"><span
                                class="ereceipt-note">E-RECEIPT</span></br>Transaction No: <span
                                style="color:#00A551; text-align:right;"> {{ $datas->voucher }} </span></th>


                    </table>
                    <table role="presentation" class="main" width="100%">

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper2" valign="top">
                                <table style="width:100%; border-spacing: 10px;" class="table">

                                    <tr>
                                        <td style="border: 1px solid #dddd" class="valign-top">
                                            <h3 class="section-title">Contact Detail</h3>
                                            <table class="table table-detail">
                                                <tr>
                                                    <td>Name</td>
                                                    <td>:</td>
                                                    <td><b> {{ $datas->user->gender == 'L' ? 'Mr.' : 'Mrs.' }}
                                                            {{ $datas->user->name }} </b></td>
                                                </tr>
                                                <tr>
                                                    <td>E-mail</td>
                                                    <td>:</td>
                                                    <td><b> {{ $datas->user->email }} </b></td>
                                                </tr>
                                                <tr>
                                                    <td>Phone No.</td>
                                                    <td>:</td>
                                                    <td><b> {{ $datas->user->phone }} </b></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border: 1px solid #dddd" class="valign-top">
                                            <h3 class="section-title">Payment Detail</h3>
                                            <table class="table table-detail">
                                                <tr>
                                                    <td>Payment Received</td>
                                                    <td>:</td>
                                                    <td><b> {{ $datas->payment_date }} </b></td>
                                                </tr>

                                                <tr>
                                                    <td>Status</td>
                                                    <td>:</td>
                                                    <td><b> {{ $datas->approve }} </B></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>


                                <table style="width:100%;" class="table">
                                    <h3 class="section-title2">Information</h3>
                                </table>
                                <table class="dgolf-wrapper2" role="presentation" border="0" cellpadding="0"
                                    cellspacing="0" width="100%">
                                    <tr>
                                        <td class="wrap-td2" valign="top">
                                            <p class="welcome-msg2"> {{ $datas->event->title }} </p>
                                            <p style="padding-bottom:10px;" class="normal-teks2"><i>
                                                    {{ $datas->event->eventCommonity->title }} </i></p>
                                            <p style="padding-bottom:10px;" class="normal-teks2">
                                                {{ $datas->event->type_scor }} - {{ $datas->event->play_date_start }}
                                            </p>
                                            <p class="normal-teks2"><b> {{ $datas->event->golfCourseEvent->name }} </b>
                                            </p>
                                            <p class="normal-teks2">{{ $datas->event->golfCourseEvent->address }}</p>
                                        </td>
                                    </tr>
                                </table>


                                <table style="width:100%; border-spacing: 10px;" class="table">

                                    <tr>
                                        <td class="valign-top">

                                            <table class="table table-detail">
                                                <tr>
                                                    <td style="" class="no-table1">No</td>
                                                    <td style="" class="item-table1">Item </td>
                                                    <td style="" class="amount-table1">Amount</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:10px; text-align:center; border-bottom:1px solid #dddd;"
                                                        class="no-table2">1</td>
                                                    <td
                                                        style="padding:10px; text-align:left;border-bottom:1px solid #dddd;">
                                                        {{ $datas->event->title }} </td>
                                                    <td
                                                        style="padding:10px; text-align:right;font-weight:bold;border-bottom:1px solid #dddd;">
                                                        {{ $datas->event->price }} </td>
                                                </tr>

                                            </table>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                                width="100%">
                                                <tr>
                                                    <td valign="top">
                                                        <p style="padding:10px; text-align:right;font-weight:bold;">
                                                            Total {{ $datas->event->price }} </p>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                                width="100%">
                                                <tr>
                                                    <td style="text-align:right;"><img src="images/stamp-01.png"
                                                            valign="top">
                                                    </td>
                                                </tr>
                                            </table>




                                    </tr>
                                </table>

                        <tr>
                            <td id="gradient3">
                                <div width="100%">
                                    <div style="width:250px; float:left;"><a><img src="http://dgolf-be.aksiteknologi.com/images/logo-dgolf4.png"></a>
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
