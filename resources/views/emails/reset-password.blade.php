<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #e65550;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>
<div class="reset-wrapper" style="width:50%; margin:2rem auto;">
    @if(!empty(@$error))
            <div class="alert alert-success" role="alert">
            {{ @$error }}
        </div>
    @endif
    @if(!@$error)
    <h2>Reset Password</h2>
    <form action="{{ url('submit-reset-password') }}" method="post" style="padding:2rem">
        @csrf
           <input type="hidden" name="token" value= "{{$token}}">
            <label for="uname"><b>New Pasword</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>
            @error('password')
                {{ $message }}
            @enderror
            <label for="psw"><b>Confirm Password</b></label>
            <input type="password" placeholder="Confirm Password" name="changed_password" required>
             @error('changed_password')
                {{ $message }}
            @enderror
            <button type="submit">Reset</button>

    </form>
    @endif

</div>
</body>
</html>
