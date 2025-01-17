<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppXpay</title>
    <style>
    table td {border-collapse:collapse;}
    @media screen and (max-device-width: 430px), screen and (max-width: 430px) { 
            td[class=emailcolsplit]{
                width:80%!important; 
                float:left!important;
                padding-left:30px!important;
                max-width:430px !important;
            }
        td[class=emailcolsplit] img {
        margin-bottom:20px !important;
        }
    } 
    </style>
</head>

    <body style="width:100% !important; margin:0 !important; padding:0 !important; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:#FFFFFF;">
        <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="height:auto !important; margin:0; padding:0; width:100% !important; background-color:#fdfcfc; color:#222222;">
            <tr>
                <td>
                 <div id="tablewrap" style="width:100% !important; max-width:600px !important; text-align:center !important; margin-top:0 !important; margin-right: auto !important; margin-bottom:0 !important; margin-left: auto !important;">
                      <table id="contenttable" width="600" align="center" cellpadding="0" cellspacing="0" border="0" style="background-color:#FFFFFF; text-align:center !important; margin-top:0 !important; margin-right: auto !important; margin-bottom:0 !important; margin-left: auto !important; border:none; width: 100% !important; max-width:600px !important;box-shadow: 0 0 1px !important;">
                    <tr>
                        <td width="100%">
                            <table bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td width="100%" bgcolor="#ffffff" style="text-align:center;"><a href="#"><img src="{{asset('new/img/appxpay-admin-logo.png')}}" alt="AppXpay" style="display:inline-block; width:30% !important; height:auto !important;border-bottom-right-radius:8px;border-bottom-left-radius:8px;margin-top: 20px;" border="0"></a>
                                    </td>
                                </tr>
                           </table>
                           <table bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="25" width="100%">
                                <tr>
                                    <td width="100%" bgcolor="#ffffff" style="text-align:left;">
                                        <h4 style="font-size: 26px;font-weight: 600;color: #5e5e5e !important;font-family:Arial, Helvetica, sans-serif;">Hello {{$htmldata["name"]}}</h4>
                                        <p style="color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:17px; line-height:19px; margin-top:0; margin-bottom:20px; padding:0; font-weight:normal;">
                                            Dear {{$htmldata["name"]}},                                  
                                        </p>
                                        <p style="color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:17px; line-height:23px; margin-top:0; margin-bottom:20px; padding:0; font-weight:normal;">
                                            You are receiving this email because we received an Email change request for your account.
                                        </p>
                                        <h4 style="text-align: center;">Please use this below OTP for changing your email address </h4>
                                        <h1 style="text-align: center;">{{$htmldata["otp"]}}</h1>
                                        <p>If you did not request a change Email, no further action is required.</p>
                                        <p style="color:brown"><strong>Note:<strong>Successfully changing of email redirects you to login page again!</p>
                                            <table border="0" cellspacing="0" cellpadding="0" width="100%" class="emailwrapto100pc">
                                                <tr>
                                                  <td class="emailcolsplit" valign="top" width="100%">
                                                      <p style="color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:17px; line-height:23px; margin-top:0; margin-bottom:20px; padding:0; font-weight:normal;">
                                                         We're here to help if you need it. Email us <span style="color: #178ddb;text-decoration: underline;font-weight: 550;"><a href="mailto:support@appxpay.in">support@AppXpay.co.in</a></span> for more info or <span style="color: #178ddb;text-decoration: underline;font-weight: 550;"><a href="mailto:hello@appxpay.in">hello@AppXpay.in</a> </span></p>
    
                                                         <p style="color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:17px; line-height:23px; margin-top:0; margin-bottom:20px; padding:0; font-weight:normal;"><span style="font-weight: 1000;">--</span> Customer Support</p>
                                                  </td>
                                                
                                                </tr>
                                                <tr>
                                          
                                                  <td class="emailcolsplit" align="left" valign="top" width="100%" style="background-color: #061f5f; padding: 30px;">
                                                      <p style="color:rgb(214, 212, 212); font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; font-weight:normal;">
                                                         Question ? Call +91-9898989898
                                                      </p>
                                                      <p style="color:rgb(214, 212, 212); font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; font-weight:normal;">
                                                          This account email has been sent to you as part of you are AppXpay Merchant.
                                                       </p>
                                                       <p style="color:rgb(214, 212, 212); font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; font-weight:normal;">
                                                          Please do not reply to this email, as we are unable to respond from this email address. If you need help or would like to contact us, please visit our Help Center at <a href="mailto:support@AppXpay.in">support@AppXpay.in</a><span style="text-decoration: underline;font-weight: 550;letter-spacing: 1px;">support@AppXpay.in</span></a>
                                                       </p>
                                                       <p style="color:rgb(214, 212, 212); font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; font-weight:normal;">
                                                          This message was mailed to [{{$htmldata["email"]}}] by appxpay
                                                       </p>
                                                       <p style="color:rgb(214, 212, 212); font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; font-weight:normal;">
                                                          Use the AppXpay Service and website is subjected to our <a href="{{secure_url('/term')}}"><span style="text-decoration: underline;font-weight: 550;letter-spacing: 1px;">Term&Condition</span></a> and <a href="{{secur_url('/privacy')}}"><span style="text-decoration: underline;font-weight: 550;letter-spacing: 1px;">Privacy Policy</span></a>
                                                       </p>
                                                       <p style="color:rgb(214, 212, 212); font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; font-weight:normal;">
                                                       AppXpay, #175 & 176, Bannerghatta Main Road, Dollars Colony, Phase 4, J.P. Nagar, Bengaluru, Karnataka-560076
                                                       </p>
                                                  </td>
                                              
                                                
                                                </tr>
                                            </table>
            
                                    </td>
                                </tr>
                           </table>
                         
                          
                        </td>
                    </tr>
                </table>
                </div>
                </td>
            </tr>
        </table> 
        </body>

</html>