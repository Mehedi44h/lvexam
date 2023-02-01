@extends('layouts/layout-common')
@section('space-work')
 <h1>Login</h1>
 @if($errors->any())
 
  @foreach ($errors->all() as $err)
      <p style="color: red;">{{$err}}</p>
  @endforeach
     
 @endif

 @if (Session::has('error'))
 <p style="color: red;">{{Session::get('error')}}</p>
     
 @endif
<form action="{{route('userlogin')}}" method="POST" >

@csrf

<input type="email " name="email" placeholder="enter email">
<br><br>
<input type="password " name="password" placeholder="enter password">
<br><br>
<input  type="submit" value="Login">

</form>
<a href="/forget_pass">Forgot password</a>

@if(Session::has('success'))
 <p style="color: green;">{{Session::get('success')}}</p>
@endif
    
@endsection