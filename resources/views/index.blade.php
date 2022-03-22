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
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'APILogger') }}
            </a>
        </div>
    </nav>

    <main class="p-4">
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
            <div class="list-group-item list-group-item-action my-3">
                <div class="row w-100" style="border-bottom: 2px solid lightgray">
                    <span class="col-md-3">
                        @if ($log->response>=400)
                            <button class="btn btn-danger font-weight-bold">{{$log->method}}</button>
                        @elseif($log->response>=300)
                            <button class="btn btn-info font-weight-bold">{{$log->method}}</button>
                        @else
                            <button class="btn btn-{{$log->method=="GET"? "primary" : "success"}} font-weight-bold">{{$log->method}}</button>
                        @endif

                        <small class="col-md-2">
                            <b>{{$log->response}} - {{url($log->url)}}</b>
                        </small>
                    </span>
                    <div class="col-md-3">
                        <p class="mb-0"><b>Duration : </b>{{$log->duration * 1000}}ms<br />
                        <b>Models(Retrieved) :</b><br />{!! empty($log->models) ? 'None' : implode('<br />', explode(', ', $log->models)) !!}</p>
                    </div>
                    <div class= "col-md-3">
                        <p class="mb-0"><b>IP :</b> {{$log->ip}}<br />
                        <b>Date : </b>{{$log->created_at}}</p>
                    </div>
                    <div class="col-md-3 mb-1">
                        <p class="mb-0"><b>Controller :</b> {{ empty($log->controller) ? 'None' : $log->controller }}<br />
                        <b>Method :</b>  {{ empty($log->action) ? 'Closure' : $log->action }}</p>
                    </div>
                </div>
                <h5 class="mt-3">Payload:</h5>
                <div class="w-100">
                     @dump(json_decode($log->payload, true))
                </div>
            </div>
            @empty
            <h5>
              No Records
            </h5>
          @endforelse

        </div>
    </main>
</body>
</html>

