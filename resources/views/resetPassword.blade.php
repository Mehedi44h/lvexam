@extends('layouts/layout-common')
@section('space-work')
 <h1>Reset  Password</h1>
 
 @if($errors->any())
 @foreach ($errors->all() as $err)
      <p style="color: red;">{{$err}}</p>
  @endforeach
 @endif



<form action="{{route('resetPassword')}}" method="POST" >

@csrf
<input type="hidden" name="id" value="{{$user[0]['id']}}">
<input type="password " name="password" placeholder="enter password">
<br><br>
<input type="password " name="password_confirmation" placeholder="enter confirm password">
<br><br>

<input  type="submit" value="Reset Password">

</form>


@if(Session::has('success'))
 <p style="color: green;">{{Session::get('success')}}</p>
@endif
    
@endsection