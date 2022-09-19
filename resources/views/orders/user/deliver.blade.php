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
        <td >
            <h2><strong>ORDER DELIVERED</strong></h2>
            <strong>{{ ucfirst($userData['name'])}} Your Order Has Been Delivered.</strong> <br />
        </td>
        <td>
            <h2><strong>SUMMARY</strong></h2>
            Order No: {{ $userData['orderDate'] }}<br />
            Order Date: {{ date('Y-m-d') }}<br />
            @if(!blank($userData['coupon']) )Coupon:&nbsp;&nbsp; {{ $userData['coupon'] }}<br />Discount:&nbsp;&nbsp; {{ $userData['discount'] }}%<br />Sub Total:&nbsp;&nbsp;AED {{ $userData['sub_total'] }}<br />@endif <br />
            Sub Total:&nbsp;&nbsp; AED {{ $userData['sub_total'] }}
            Shpping Charges:&nbsp;&nbsp; AED {{ $userData['shipping_charges'] }}
            Total:&nbsp;&nbsp; AED {{ $userData['total'] }}
        </td>
    </tr>
    <br/><br/>
    @foreach($userData['product_detail'] as $value)<tr><td colspan="1" ><img  src="{{ $value['product_variation'][0]['avatar'] }}}" alt="" height="130"/></td><td colspan="2" style="line-height:23px;">{{ $value['product_name'] }}<br/>
            Quantity: &nbsp; &nbsp;{{ $value['qty']}}<br /> Price:&nbsp; &nbsp; AED&nbsp;{{ $value['price'] }}<br />@endforeach
            <br/><br/>
    <tr><h1>Shipping Address</h1>
        <p> {{ $userData['address_name'] }} {{ $userData['address_country']  }} {{ $userData['address_state'] }} {{  $userData['address_city'] }}

        </p>
        <p> {{$userData['address_line1'] }} {{ $userData['address_line2'] }} {{ $userData['postal_code'] }} {{ $userData['phone_number'] }}

        </p>

    </tr>
    <tr >
        <td >
            <p ><strong>Thank you for the order!</strong><br/> &nbsp; &nbsp; For more inquiries please send an e-mail to <span>tanuja@prism-me.com &nbsp;&nbsp;</span></p>
        </td>

              <td style="text-align:left;width:50%;">
                        <a href="https://www.instagram.com/bafco/" target="blank" style="text-decoration:none;"><i class="icon-instagram"></i></a>
                        <a href="https://www.facebook.com/bafcointeriors" target="blank" style="text-decoration:none;"><i class="icon-facebook-f"></i></a>
                        <a href="https://www.linkedin.com/company/bafco/" target="blank" style="text-decoration:none;"><i class="icon-linkedin"></i></a>
                        <a href="https://twitter.com/Bafco" target="blank" style="text-decoration:none;"><i class="icon-twitter"></i></a>
                        <a href="https://www.pinterest.com/bafcointeriors/" target="blank" style="text-decoration:none;"><i class="icon-pinterest"></i></a>
               </td>
            </tr>

    </tbody>
</table>
