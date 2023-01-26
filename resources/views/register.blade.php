@extends('layouts/layout-common')
@section('space-work')
 <h1>Register</h1>
 @if($errors->any())
 
  @foreach ($errors->all() as $err)
      <p style="color: red;">{{$err}}</p>
  @endforeach
     
 @endif
<form action="{{route('studentRegister')}}" method="POST" >

@csrf


<input type="text " name="name" placeholder="enter name">
<br><br>
<input type="email " name="email" placeholder="enter email">
<br><br><input type="password " name="password" placeholder="enter password">
<br><br><input type="password " name="password_confirmation" placeholder="enter confirm password">
<br><br>
<input type="submit" value="Register">
</form>
@if(Session::has('success'))
 <p style="color: green;">{{Session::get('success')}}</p>
@endif
    
@endsection