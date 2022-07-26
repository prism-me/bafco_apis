<table class="table table-bordered" style="width:605px;">
    <thead>
        <tr>
        <th colspan="3" style="border-bottom:0px !important;position: relative;">
            <img src="https://bafco.b-cdn.net/images/Rectangle%2032.png" alt="logo" >
            <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="position:absolute; top:10px; left:0; right:0; margin:0 auto; width:200px; background:white;">
        <br />
        </th>
        </tr>
    </thead>
    <tbody>
      <td colspan="2">
          <p>Hi {{  $user['name'] }},</p>
          <p>Welcome to the Bafco</p>
          <p>This is your Verification code:
              <input type="text" value="{{  $user['code'] }}"></p>
          <p>Thanks,</p>
          <p>Bafco Team</p>
          <p style="color:red">*This Link is Valid only For 30 minutes only</p>

      </td>
    <td>

    </td>

</tbody>
</table>
