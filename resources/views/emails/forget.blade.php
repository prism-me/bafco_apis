<html>
    <body>
        <table class="table table-bordered" style="width:550px !important;"> 
            <tbody>
                <tr>
                    <td colspan="3" style="text-align:center; line-height:24px;">
                        <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="max-height: 50px; display:block;margin:0px auto;" height="70" with="auto" align="center"/>
                        <p style="color:#a39f9f; text-align:center;font-size:14px;"><strong>Hello! {{  ucwords($user['name'] )}}</strong></p>
                        <h2 style="text-align:center; color:#e65550;"><strong>Forget Password!</strong></h2>  
                        <p style="color:#a39f9f; text-align:center;font-size:14px;"><strong>Click the Link below to Reset Your Password</strong></p><br />
                        <a href="{{ url('reset-password', $user['token']) }}"  style="background-color: #e65550;color: white;border: none;height: 35px;border-radius: 13px;padding: 10px;text-decoration: none;">Reset Password</a><br/><br />
                        <p style="color:#e65550; text-align:center;font-size:14px;"><strong>*This Link is Valid only For 30 minutes only</strong></p><br />
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>