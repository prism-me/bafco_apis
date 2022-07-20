<table class="table table-bordered" style="width:605px !important;"> 
    <thead>
        <tr>
        <th colspan="3" style="border-bottom:0px !important;">
        <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="max-height: 50px; display:block;margin:0px auto;" height="50" with="auto"/>
        <br />
        </th>
        </tr>
    </thead>
    <tbody>
      <td colspan="2" style="text-align:center; line-height:24px;">
        <h2 style="color:#a39f9f;text-align:center">Hello! {{  $user['name'] }}</p></h2>
        <h2 style="text-align:center; color:#e65550;"><strong>Email Verification!</strong></h2>  
        <p style="color:#a39f9f; text-align:center"><strong>Click the Link below to Verify Email</strong></p><br />
        <p style="color:#e65550; text-align:center"><strong>*This Link is Valid only For 30 minutes only</strong></p><br />
        <a href="{{url('email-verification', $user['token']) }}"  style="background-color: #e65550;color: white;border: none;height: 35px;border-radius: 13px;padding: 10px;text-decoration: none;">Verify Email</a></td>
        </tr>
      
</tbody>
</table>
