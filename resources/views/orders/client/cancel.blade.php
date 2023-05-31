<table class="table table-bordered" align="center" style="width:605px;margin:2rem auto;" cellspacing="0" cellpadding="0">
    <style>
        td {vertical-align: top !important;}
    </style>
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
                    ORDER CANCELLATION</td>
            </tr><br />
            <tr>
                <td  colspan="3">
                    <p style="text-align:center; color: #f44336"><b>{{ ucfirst($userData['name'])}}  with Order Number {{ $userData['orderNumber'] }} wants to cancel the order.</b></p>
                    <p style="text-align:center; color: #008482;"><strong>Reason for cancellation :</strong>&nbsp; {{  $userData['cancellationReason'] }}</p>
                </td>
            </tr><br />
            <tr>
                <td colspan="2">
                    <h2><strong>Shipping Address</strong></h2>
                    {{ $userData['address_name'] }} <br />{{ $userData['address_country']  }}  {{ $userData['address_state'] }} {{  $userData['address_city'] }} <br />
                    {{$userData['address_line1'] }} {{ $userData['address_line2'] }}  < {{ $userData['postal_code'] }} > <br /> {{ $userData['phone_number'] }}
                </td>
                <td colspan="2">
                    <h2><strong>Order Summary</strong></h2>
                    <b>Order No:</b> {{ $userData['orderNumber'] }} <br />
                    <b>Order Date: </b>{{ date('Y-m-d') }}<br />
                        @if($userData['coupon'] != "BAFCOTest" ) <b>Coupon:</b>&nbsp;&nbsp;{{ $userData['coupon'] }} <br /> <b>Discount:&nbsp;</b>{{ $userData['discount'] }}<br />@endif <br />
                    &nbsp; <b>Sub Total:</b>&nbsp;&nbsp; AED {{ $userData['sub_total'] }} <br />
                    &nbsp; <b>Shpping Charges:</b>&nbsp;&nbsp; {{ $userData['shipping_charges'] }}<br />
                    &nbsp; <b>Total:&nbsp;</b>&nbsp; AED {{ $userData['total'] }}<br />
                </td>
            </tr>
        <br/>
            @foreach($userData['product_detail'] as $value)<tr><td colspan="1" align="center" ><img  src="{{ $value['product_image'][0]['avatar'] }}" alt="" height="130"/></td><td colspan="3" style="line-height:23px;"><b>&nbsp;&nbsp;Name:</b> &nbsp; &nbsp;{{ $value['product_name'] }}<br/><b>&nbsp;&nbsp;Quantity: </b>&nbsp; &nbsp;{{ $value['qty']}}<br /> <b>&nbsp;&nbsp;Price:</b>&nbsp; &nbsp; AED&nbsp;{{ $value['price'] }}<br /> @if($value['in_stock'] == 1)<b> &nbsp;&nbsp;Stock: </b>In Stock @else Out of stock @endif </b> <br />
            @foreach($value['product_variation'] as $variation) <b>{{ $variation['variation_name']['name'] }}: </b> &nbsp; &nbsp;{{ $variation['variation_values']['name']  }}<br />@endforeach</tr></td><br />
            @endforeach
        <br/><br/>
            <tr>
                <td height="100" colspan="3"><br>&nbsp;
                    <table class="footer" align="center"  height="100" width="570" role="presentation" style="padding: 15px 0px 9px 0px; background: #f2f2f2;margin: 0px; width:100%">
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
