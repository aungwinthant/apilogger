<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'APILogger') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"></head>
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 
<body style="font-family: 'Nunito', sans-serif;font-size: 0.9rem;line-height: 1.6">
    <div class="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'APILogger') }}
                </a>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="w-100 d-flex justify-content-between">
                    <h3 class="text-center">Api Logger</h3>
                    <form method="POST" action="{{ route('apilogs.deletelogs') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger delete-logs" value="Delete Logs">
                        </div>
                    </form>
                </div>
                <div class="list-group">
                    @forelse ($apilogs as $key => $log)
                    <div class="list-group-item list-group-item-action" style="margin:5px">
                        <div class = "row w-100">
                            <span class="col-md-3">
                                @if ($log->response>400)
                                    <button class="btn btn-danger font-weight-bold">{{$log->method}}</button>
                                @elseif($log->response>300)
                                    <button class="btn btn-info font-weight-bold">{{$log->method}}</button>
                                @else
                                    <button class="btn btn-{{$log->method=="GET"? "primary" : "success"}} font-weight-bold">{{$log->method}}</button>
                                @endif
                                
                                <small class="col-md-2">
                                    <b>{{$log->response}}</b>
                                </small>
                            </span>
                            <large class= "col-md-3"><b>Duration : </b>{{$log->duration * 1000}}ms</large>
                            <large class= "col-md-3"><b>Date : </b>{{$log->created_at}}</large>
                            <p class="col-md-3 mb-1"><b>IP :</b> {{$log->ip}}</p>
                        </div>
                        <hr>
                        <div class="row w-100">
                            <p class="col-md-3 mb-1">
                                <b>URL : </b>{{$log->url}}</br>
                            </p>
                            <p class="col-md-6 mb-1"><b>Models(Retrieved) :</b> {{$log->models}}</p>
                        </div>
                        <div class="row w-100">
                                <p class="col-md-3">
                                    <b>Method :</b>   {{$log->action}}
                                </p>
                                <p class="col-md-3 mb-1"><b>Payload : </b>{{$log->payload}}</p>

                                <p class="col-md-6">
                                    <b>Controller :</b> {{$log->controller}}
                                    
                                </p>
                            
                        </div>
                    </div>
                    @empty
                    <h5>
                      No Records
                    </h5>
                  @endforelse
                
                </div>
            </div>
        </main>
    </div>
</body>
</html>

