@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 align="center">Account Details</h2></div>

                    <div class="panel-body row">
                        <div class="container col-sm-4" align="center">
                            @if($portrait)
                                <h3>Profile picture</h3>
                                <img class="img-responsive" src="{{asset('Pictures/'.$admin)}}" width="300">
                                <br>
                                <a href="{{ route ( "picture", ["choice" => "default"] ) }}" class="btn btn-default">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    Picture</a>

                            @else
                                <h3>You don't have a profile picture</h3>
                                <a href="{{ route ( "picture", ["choice" => "default"] ) }}" class="btn btn-default">Add picture</a>
                            @endif
                        </div>
                        <div class="container col-sm-4" align="center">
                            <h3>Username: <b>{{$user->name}}</b></h3>
                            <h3>Description:</h3>
                            <h4><b><i><q>{{$user->description}}</q></i></b></h4>
                            <h3>Category: <b>{{$categoryName}}</b></h3>
                        </div>
                        <div class="container col-sm-4" align="center">
                            <a href="{{route("adminname")}}" class="btn btn-default edit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Username</a>

                            <a href="{{route("admindescription",["adminID"=>$user->id])}}" class="btn btn-default edit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Description</a>

                            <a href="{{route("categorysettings")}}" class="btn btn-default edit">
                                <i class="fa fa-wrench" aria-hidden="true"></i>
                                Category</a>

                        </div>

            </div>
        </div>
    </div>
    </div>
@endsection
