@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (isset($status))
                            @if(substr($status, 0, 3) != 'Err')
                                <div class="alert alert-success">
                                    <strong>Success! </strong> {{$status}}
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <strong>Error! </strong> {{$status}}
                                </div>
                            @endif
                    @else
                            You are logged in!
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
