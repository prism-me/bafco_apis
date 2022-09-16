    <table class="table table-bordered" style="width:635px !important;">
        <thead>
        <tr>
            <th colspan="3" style="border-bottom:0px !important;">
                    <img src="https://bafco.b-cdn.net/images/bafco-logo.png" alt="logo" style="max-height: 50px; display:block;margin:0px auto;" height="70" with="auto" align="center"/>
                <br />
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="50%" style="line-height:24px;">
                <h2><strong>ORDER CANCELLATION</strong></h2>
                <strong>{{ ucfirst($userData['name'])}} Your Order Has Been Cancelled.</strong> <br />
            </td>
            <td width="50%" style="text-align:right; line-height:24px;">
                <h2 style="text-align:right;"><strong>SUMMARY</strong></h2>
                Order No: {{ $userData['orderDate'] }}<br />
                Order Date: {{ date('Y-m-d') }}<br />
                @if(!blank($userData['coupon']) )Coupon:&nbsp;&nbsp; {{ $userData['coupon'] }}<br />Discount:&nbsp;&nbsp; {{ $userData['discount'] }}%<br />Sub Total:&nbsp;&nbsp;AED {{ $userData['sub_total'] }}<br />@endif <br />
                Sub Total:&nbsp;&nbsp; AED {{ $userData['sub_total'] }}
                Shpping Charges:&nbsp;&nbsp; AED {{ $userData['shipping_charges'] }}
                Total:&nbsp;&nbsp; AED {{ $userData['total'] }}
            </td>
        </tr>
        <br/><br/>
        @foreach($userData['product_detail'] as $value)<tr><td colspan="1" align="center" ><img  src="{{ $value['product_variation'][0]['avatar'] }}}" alt="" height="130"/></td><td colspan="2" style="line-height:23px;">{{ $value['product_name'] }}<br/>
                Quantity: &nbsp; &nbsp;{{ $value['qty']}}<br /> Price:&nbsp; &nbsp; AED&nbsp;{{ $value['price'] }}<br />@endforeach
        <br/><br/>
        <tr><h1>Shipping Address</h1>
            <p> {{ $userData['address_name'] }} {{ $userData['address_country']  }} {{ $userData['address_state'] }} {{  $userData['address_city'] }}

            </p>
            <p> {{$userData['address_line1'] }} {{ $userData['address_line2'] }} {{ $userData['postal_code'] }} {{ $userData['phone_number'] }}

            </p>

        </tr>
        <tr style="color:white; background-color:#e65550;text-align:center;">
            <td colspan="3" style="color:white; text-align:center;margin:.5rem 0px 0px;padding: 10px 0 0 !important;">
                <p style="color:white; text-align:center"><strong>Thank you for the order!</strong><br/> &nbsp; &nbsp; For more inquiries please send an e-mail to <span style="color:white">tanuja@prism-me.com &nbsp;&nbsp;</span></p>
            </td>
        </tr>
{{--        <tr style="color:white; background-color:#e65550;text-align:center;">--}}
{{--            <td style="text-align:right;width:50%;">--}}
{{--                <a href="https://pigeonarabia.com/en/mother-baby-products" style="color:#ffffff; text-decoration:none;padding:2px;font-size:12px;">Products</a>--}}
{{--                <a href="https://pigeonarabia.com/en/contact" style="color:#ffffff; text-decoration:none;padding:2px;font-size:12px;">Contact Us</a>--}}
{{--                <a href="https://pigeonarabia.com/en/about" style="color:#ffffff; text-decoration:none;padding:2px;font-size:12px;">About</a>--}}
{{--            </td>--}}
{{--            <td style="text-align:left;width:50%;">--}}
{{--                &nbsp; &nbsp;&nbsp; &nbsp;<a href="https://web.facebook.com/pigeonmena/?_rdc=1&amp;_rdr" target="blank" style="text-decoration:none;"><img  src="https://american-gulf-school.b-cdn.net/logo/facebook.png" style="height: 25px;border-radius: 4px; display:inline-block;Margin:3px;" height="26">&nbsp;&nbsp;</a><a href="https://www.instagram.com/pigeonarabia/" target="blank" style="text-decoration:none;"><img  src="https://american-gulf-school.b-cdn.net/logo/instagram.png" style="height: 25px;border-radius: 4px;display:inline-block;Margin:3px;" height="26">&nbsp;&nbsp;</a><a href="https://www.youtube.com/channel/UCfI-xamtRK4LJ5ABg5msyeQ/about" target="blank" style="text-decoration:none;"><img  src="https://american-gulf-school.b-cdn.net/logo/youtube.png" style="height: 25px;border-radius: 4px; display:inline-block;Margin:3px;" height="26">&nbsp;&nbsp;</a>--}}
{{--            </td>--}}
{{--        </tr>--}}

        </tbody>
    </table>
