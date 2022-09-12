@component('mail::message')
    <table class="table table-bordered" style="width:550px !important;">
        <thead>
        <tr>
            <th colspan="3" style="border-bottom:0px !important;">
                <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="max-height: 50px; display:block;margin:0px auto;" height="70" with="auto" align="center"/>
                <br />
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style="background-color:#e65550;padding:.5rem 0px;color:white">
            <td colspan="3" align="center"><p style="color:white;text-align:center">Order No: </p></td></tr><br/><br/>
        <tr>
            <td colspan="3">
                <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="max-height: 50px; display:block;margin:0px auto;" height="70" with="auto" align="center"/>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center; line-height:24px;">
                <h2 style="text-align:center; color:#e65550;"><strong>Order Cancelled</strong></h2>
                <p style="color:#a39f9f; text-align:center;font-size:14px;"><strong>Hello  </strong></p><p style="color:#a39f9f; text-align:center;font-size:14px;"><strong>Your order with Order Number {{ $clientMailData['orderNo'] }} has been delivered.We hope you enjoy your purchase.</strong></p><br />
            </td>
        </tr>

        </tbody>
    </table>
@endcomponent
