@component('mail::message')
    <h1 style="text-align: center;">Welcome To Bafco </h1>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>Name</td>
            <td>{{$data['name']}}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{$data['email']}}</td>
        </tr>
        <tr>
            <td>Subject</td>
            <td>{{$data['subject']}}</td>
        </tr>
        <tr>
            <td>Message</td>
            <td>{{$data['message']}}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>{{$data['type']}}</td>
        </tr>
        </tbody>
    </table>
    Thank you for your contact, we'll get in touch with you shortly.<br>
    {{ config('app.name') }}
@endcomponent
