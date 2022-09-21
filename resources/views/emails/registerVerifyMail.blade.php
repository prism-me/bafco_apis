<table class="table table-bordered" align="center" style="width:605px;margin:2rem auto;" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th colspan="3" style="border-bottom:0px !important;position: relative;line-height: 0px;">
                <div style="background-image:url('https://bafco.b-cdn.net/images/Rectangle%2032.png');height:100px;display:flex;flex-direction:row;justify-content:center;align-items:center;background-size:cover;">
                    <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="margin: 15px auto;" height="70">
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="3"
                style="background-color:#008482;text-align:center;color:white;font-weight:bold;font-size:18px; height:56px;">
                Verification Code</td>
        </tr>
        <tr>
            <td colspan="2">
                <br>&nbsp;
                <p>Hi {{ $user['name'] }},</p>
                <p>Welcome to the Bafco, your Verification code is: <span
                        style="font-weight:bold;font-size:15px;">{{ @$user['code'] }}</span></p>
                <p>Thanks,</p>
                <p>Bafco Team</p>
                <p style="color:#008482">*This Link is Valid only For 30 minutes only</p>

            </td>
            <td>

            </td>
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
