<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>OTP Login</title>
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
  .body{border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;}
  .main {border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%; }
  #gradient2 {background-image: linear-gradient(to right, #00A551, #140958); height:10px;}
  #gradient3 {background-image: linear-gradient(to right, #00A551, #140958); height:130px; padding:35px 50px;}
  .content {box-sizing: border-box; display: block; margin: 0 auto;  max-width: 880px; padding: 10px;}
  .dgolfbody{background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 20px; line-height: 1.9; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;}
  .container{font-family: sans-serif; font-size: 20px; vertical-align: top; display: block; max-width: 880px; padding: 10px; width: 880px; margin: 0 auto;}
  .driving{font-family: sans-serif; font-size: 20px; vertical-align: top;}
  .logodgolf{padding:30px 50px;}
  .wrapper{font-family: sans-serif; font-size: 20px; vertical-align: top; box-sizing: border-box; padding: 20px 50px;}
  .dgolf-wrapper{border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;}
  .wrap-td{font-family: sans-serif; font-size: 20px; vertical-align: top;}
  .welcome-msg{font-family: sans-serif; font-size: 24px; font-weight: bold; margin: 0; margin-bottom: 15px;}
  .normal-teks{font-family: sans-serif; font-size: 20px; font-weight: normal; margin: 0; margin-bottom: 25px;}
  .cs-email{color:white; font-size:18px; font-weight:bold;line-height:20px;}
  .footer-cs{color:white; font-size:13px;}
  .otp-number{font-family: sans-serif; font-size: 35px; font-weight:s bold; margin-top: 10px; margin-bottom: 35px; border-radius: 20px; background-color:#00A551;width:150px; text-align:center; color:white;}
}
</style>
  </head>
  <body class="dgolfbody">
    <table class="body" role="presentation" border="0" cellpadding="0" cellspacing="0"  width="100%" bgcolor="#f6f6f6">
      <tr>
        <td class="driving" valign="top"></td>
        <td class="container" width="680" valign="top">
          <div class="content">
            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main" width="100%">
			<tr><td id="gradient2"></td></tr>
			<tr><td class="logodgolf"><img src="http://dgolf-be.aksiteknologi.com/images/logo-dgolf3.png"/></td></tr>
              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" valign="top">
                  <table class="dgolf-wrapper" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td class="wrap-td" valign="top">
                        <p class="welcome-msg">Hi {{ $user->name }}</p>
						<p class="normal-teks">Kami telah menerima permintaan Login pada akun Anda. Untuk mengkonfirmasi permintaan Anda, mohon masukkan Kode OTP berikut</p>
						<p class="otp-number">{{ $user->otp_code_login }}</p>
						<p class="normal-teks">Abaikan email ini jika Anda tidak pernah meminta untuk Login.</p>
						<p class="normal-teks">Butuh bantuan? Anda bisa hubungi <span style="color:#00A551;">Customer Care</span> D’Golf yang selalu siap membantu Anda!</p>
						<p class="normal-teks">Salam Hangat </br>D’Golf</p>
                      </td>
                    </tr>
                  </table>
				  <tr>
                    <td id="gradient3">
				        <div width="100%">
				        <div style="width:250px; float:left;"><a><img src="http://dgolf-be.aksiteknologi.com/images/logo-dgolf4.png"></a>
                        </div>
						<div style="float:right; margin-top:30px;">
                        <p class="footer-cs">Customer Service Email: </br><span class="cs-email">cs@dgolf.com</span></p></div>
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