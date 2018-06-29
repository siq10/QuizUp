@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 align="center">Form for name editing</h2></div>

                    <div class="panel-body" align="center">
                        <h3>Enter your desired name in the field below</h3>
                        <div class="container col-sm-6" align="center">
                            <h4>Old name: <b>{{$admin}}</b></h4>
                        </div>
                        <div class="container col-sm-6" align="center">
                                <form action="{{route("editadminname")}}" class="form-inline" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="name">New name :</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <button type="submit" class="btn btn-default">Update</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection