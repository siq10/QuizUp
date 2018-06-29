@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 align="center">Picture Form</h2></div>
                    <div class="panel-body">
                        <div class="upload">
                            <h3>Choose method to upload image:</h3>
                            <a class="btn btn-default" href="{{route('picture' , ['choice' => "local"] ) }}"> Local </a>
                            <a class="btn btn-default" href="{{route('picture' , ['choice' => "url"] ) }}"> Web </a>
                        </div>
                        <div>
                            @if(isset($choice))
                                @if($choice =="local")
                                    <form action="{{route('filesave')}}" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="file" name="file" id="file" class="inputfile" required>
                                        <label for="file" class="btn btn-default">
                                            <i class="fa fa-file-image-o" aria-hidden="true"></i>Browse
                                        </label>
                                        <button class="btn btn-default" type="submit"> <i class="fa fa-upload" aria-hidden="true"></i>
                                            <b>Upload</b>
                                        </button>
                                    </form>

                                @endif
                                @if($choice =="url")
                                        <form action="{{route('urlsave')}}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <label for="url"> Copy the image URL here:</label>
                                                <input type="text" class="form-control" id="url" name="url" placeholder="www.example.com">
                                            </div>
                                            <button class="btn btn-default" type="submit"> <i class="fa fa-upload" aria-hidden="true"></i>
                                                <b>Upload</b>
                                            </button>
                                        </form>
                                        </div>
                                    @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
