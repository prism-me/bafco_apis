<table class="table table-bordered" align="center" style="width:605px;margin:2rem auto;" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th colspan="3" style="border-bottom:0px !important;position: relative;line-height: 0px;">
                <div
                    style="background-image:url('https://bafco.b-cdn.net/images/Rectangle%2032.png');height:100px;display:flex;flex-direction:row;justify-content:center;align-items:center;background-size:cover;">
                    <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="margin: 15px auto;"
                        height="70">
                </div>
            </th>
        </tr>
    </thead>
    <tbody >
        <tr>
            <td colspan="3" style="background-color:#008482;text-align:center;color:white;font-weight:bold;font-size:18px; height:56px;">Forget Password</td>
        </tr>
        <tr align="center">
            <br> &nbsp;
            <p style="color:#a39f9f;font-size:14px;"><strong>Hi! {{ ucwords($user['name']) }}</strong></p>
            <p style="color:#a39f9f;font-size:14px;"><strong>Click the Link below to Reset Your Password</strong></p>
            <div><!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ url('reset-password', $user['token']) }}" style="height:40px;v-text-anchor:middle;width:180px;" arcsize="25%" stroke="f" fill="t">
                  <v:fill type="tile" color="#008482" />
                  <w:anchorlock/>
                  <center style="color:#ffffff;font-size:13px;font-weight:bold;">Reset Password</center>
                </v:roundrect>
              <![endif]--><a href="{{ url('reset-password', $user['token']) }}"
              style="background-color:#008482;border-radius:10px;color:#ffffff;display:inline-block;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:180px;-webkit-text-size-adjust:none;mso-hide:all;">Reset Password</a></div>  
            <p style="color:#008482;font-size:14px;"><strong>*This Link is Valid only For 30 minutes only</strong></p><br />

        </tr>
        <tr>
            <td height="100"><br>&nbsp;
                <table class="footer" align="center"  height="100" width="570" role="presentation" style="background: #f2f2f2;margin: 0px; width:100%">
                    <tr class="content-cell" align="center">
                        <table>
                            <tr align="center" cellpadding="2" cellspacing="4">
                                <td><img height="25" src="https://bafco.b-cdn.net/email_templates/fb.png" alt="fb"></td>
                                <td><img height="25" src="https://bafco.b-cdn.net/email_templates/insta.png" alt="insta"></td>
                                <td><img height="25" src="https://bafco.b-cdn.net/email_templates/twitter.png" alt="twitter"></td>
                                <td><img height="25" src="https://bafco.b-cdn.net/email_templates/linkedin.png" alt="linkedin"></td>
                            </tr>
                        </table>
                    </tr>
                    <tr>
                        <td class="content-cell" align="center">
                            Copyright © 2022 Bafco Store. All Rights Reserved.
                        </td>
                    </tr>
                </table>
            <br>&nbsp;</td>
        </tr>
        
    </tbody>
    
</table>