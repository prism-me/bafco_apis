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
        <h3 style="text-align:center;">{{ ucfirst( $data->name )}} Trying to contact Bafco Team. </h3>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>Name</td>
                    <td>{{$data->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{$data->email }}</td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>{{$data->subject }}</td>
                </tr>
                <tr>
                    <td>Message</td>
                    <td>{{$data->message }}</td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>{{$data->form_type }}</td>
                </tr>
                <tr>
                    <td>Attachment</td>
                    <td>{{ $data->attachment }}</td>
                </tr>
            </tbody>
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
                        <br>
                    </td>
                </tr>
            </tbody>
        </table>
    </tbody>
</table>
