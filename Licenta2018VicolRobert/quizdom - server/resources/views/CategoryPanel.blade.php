@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 align="center">Category Panel</h2></div>

                    <div class="panel-body" align="center">
                        <div class="container col-sm-6" align="center">
                            <h4>Change category name:</h4>
                            <h4><b>{{$categoryName}}</b></h4>
                            <a class="btn btn-default" href="{{route("categoryname")}}">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Name</a>
                        </div>
                        <div class="container col-sm-6" align="center">
                            <h4>Change category description:</h4>
                            <h4><i><b><q>{{$categoryDescription}}</q></b></i></h4>
                            <a class="btn btn-default" href="{{route("categorydescription")}}">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                Description</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection