@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 align="center">Form for category description editing</h2></div>

                    <div class="panel-body" align="center">
                        <h3>Enter your desired category description in the field below</h3>
                        <br>
                        <div class="container col-sm-6" align="center">
                            <h4>Old description:</h4>
                            <br>
                            <h4><i><b><q>{{$categoryDescription}}</q></b></i></h4>
                        </div>
                        <div class="container col-sm-6" align="center">
                            <form id="frm" action="{{route("editcategorydescription")}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="description">New category description:</label>
                                    <textarea cols="14" rows="6" maxlength="300" form="frm" class="form-control" id="description" name="description"></textarea>
                                </div>
                                <button type="submit" class="btn btn-default">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection