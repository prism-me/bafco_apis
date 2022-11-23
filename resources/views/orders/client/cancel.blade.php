<table class="table table-bordered" align="center" style="width:670px;margin:2rem auto;" cellspacing="0" cellpadding="0">
    <style>
        td {vertical-align: top !important;}
    </style>
    <thead>
    <tr>
        <th colspan="4" style="border-bottom:0px !important;position: relative;line-height: 0px;">
            <div style="background-image:url('https://bafco.b-cdn.net/images/Rectangle%2032.png');height:100px;display:flex;flex-direction:row;justify-content:center;align-items:center;background-size:cover;">
                <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="margin: 15px auto;" height="70">
            </div>
        </th>
    </tr>
    </thead>
    <tbody>
            <tr>
                <td colspan="4"
                    style="background-color:#008482;text-align:center;color:white;font-weight:bold;font-size:18px; height:56px;">
                    ORDER CANCELLATION</td>
            </tr><br />
            <tr>
                <td  colspan="4">
                    <p style="text-align:center; color: #f44336"><b>{{ ucfirst($userData['name'])}}  with Order Number {{ $userData['orderNumber'] }} wants to cancel the order.</b></p>
                    <p style="text-align:center; color: #008482;"><strong>Reason for cancellation :</strong>&nbsp; {{  $userData['cancellationReason'] }}</p>
                    <br />
                </td>
            </tr><br />
            <tr>
                <td colspan="2">
                    <p><strong>Shipping Address</strong><br />
                    {{ $userData['address_name'] }} <br />{{ $userData['address_country']  }}  {{ $userData['address_state'] }} {{  $userData['address_city'] }} <br />
                    {{$userData['address_line1'] }} {{ $userData['address_line2'] }}  < {{ $userData['postal_code'] }} > <br /> {{ $userData['phone_number'] }}
                    </p>
                </td>
                <td colspan="2" align="right">
                    <p><strong>Order Summary</strong><br />
                    <b>Order No:</b> &nbsp;{{ $userData['orderNumber'] }} <br />
                    <b>Order Date: &nbsp; </b>{{ date('Y-m-d') }} <br />
                        @if($userData['coupon'] != "BAFCOTest" ) <b>Coupon:</b>&nbsp;{{ $userData['coupon'] }} <br /> <b>Discount:&nbsp;</b>{{ $userData['discount'] }}<br />@endif
                    <b>Sub Total:</b>&nbsp; AED {{ $userData['sub_total'] }} <br />
                    <b>Shpping Charges:</b>&nbsp; {{ $userData['shipping_charges'] }}<br />
                    <b>Total:</b>&nbsp; AED {{ $userData['total'] }}<br />
                    </p>
                </td>
            </tr>
            <tr>
                <td > <br /></td>
            </tr>
            @foreach($userData['product_detail'] as $value)<tr><td colspan="1" align="center" ><img  src="{{ $value['product_image'][0]['avatar'] }}" alt="" height="130"/></td><td colspan="3" style="line-height:23px;"><b>&nbsp;&nbsp;Name:</b> &nbsp; &nbsp;{{ $value['product_name'] }}<br/><b>&nbsp;&nbsp;Quantity: </b>&nbsp; &nbsp;{{ $value['qty']}}<br /> <b>&nbsp;&nbsp;Price:</b>&nbsp; &nbsp; AED&nbsp;{{ $value['price'] }}<br />
            @foreach($value['product_variation'] as $variation) <b>&nbsp;&nbsp;{{ $variation['variation_name']['name'] }}: </b> &nbsp; &nbsp;{{ $variation['variation_values']['name']  }}<br />@endforeach</tr></td><br />
            @endforeach
        <br/><br/>
            <tr>
                <td height="100" colspan="4"><br>&nbsp;
                    <table class="footer" align="center"  height="100" width="670" role="presentation" style="padding: 15px 0px 9px 0px; background: #f2f2f2;margin: 0px; width:100%">
                        <tr>
                            <td class="content-cell" align="center">
                                Copyright © 2022 Bafco Store. All Rights Reserved.
                            </td>
                        </tr>
                    </table>
                    <br>
                </td>
            </tr>
    </tbody>
</table>
