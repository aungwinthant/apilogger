<?php
function hex_dump($data, $newline = "\n") {
    static $from = '';
    static $to = '';

    static $width = 32; # number of bytes per line

    static $pad = '.'; # padding for non-visible characters

    if($from === '') {
        for($i = 0; $i <= 0xFF; $i++) {
            $from .= chr($i);
            $to   .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
        }
    }

    $hex   = str_split(bin2hex($data), $width * 2);
    $chars = str_split(strtr($data, $from, $to), $width);

    $offset = 0;
    foreach($hex as $i => $line) {
        echo sprintf('%6X', $offset) . ' : ' . implode(' ', str_split($line, 2)) . ' [' . $chars[$i] . ']' . $newline;
        $offset += $width;
    }
}

?>
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
    <style>
        pre.sf-dump .sf-dump-compact, .sf-dump-str-collapse .sf-dump-str-collapse, .sf-dump-str-expand .sf-dump-str-expand { display: none; }
    </style>
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
            <div class="my-3 alert" style="background: #f0f0f0">
                <div class="row w-100" style="border-bottom: 2px solid lightgray">
                    <span class="col-md-3">
                        @if ($log->status_code>=400)
                            <button class="btn btn-danger font-weight-bold">{{$log->method}}</button>
                        @elseif($log->status_code>=300)
                            <button class="btn btn-info font-weight-bold">{{$log->method}}</button>
                        @else
                            <button class="btn btn-{{$log->method=="GET"? "primary" : "success"}} font-weight-bold">{{$log->method}}</button>
                        @endif

                        <small class="col-md-2" style="word-break: break-all;">
                            <b>{{$log->status_code}} - {{url($log->url)}}</b>
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
                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#payload_{{$key}}">Payload</a>
                    </li>
                    @if(config('apilog.payload_raw'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#payload_raw_{{$key}}">Payload raw</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#headers_{{$key}}">Payload Headers</a>
                    </li>
                    @if(config('apilog.response'))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#response_{{$key}}">Response</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#response_headers_{{$key}}">Response Headers</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="payload_{{$key}}">
                        @dump(json_decode($log->payload, true))
                    </div>
                    @if(config('apilog.payload_raw'))
                    <div class="tab-pane fade" id="payload_raw_{{$key}}">
                        <div class='alert-secondary p-3 mb-3'>{{$log->payload_raw}}</div>
                        <h5>HexDump</h5>
                        <pre>{{hex_dump($log->payload_raw)}}</pre>
                    </div>
                    @endif
                    <div class="tab-pane fade" id="headers_{{$key}}">
                        @dump(json_decode($log->headers, true))
                    </div>
                    @if(config('apilog.response'))
                    <div class="tab-pane fade" id="response_{{$key}}">
                        @if(config('apilog.response_autodetect'))
                            <?php
                            if(!is_null(json_decode($log->response))) {
                                dump(json_decode($log->response, true));
                            } elseif(preg_match('/<\s?[^\>]*\/?\s?>/i', $log->response)) {
                                echo '<iframe srcdoc="'.htmlentities($log->response).'" width="100%" height="500px"></iframe>';
                            } else {
                                libxml_use_internal_errors(true);
                                simplexml_load_string($log->response);
                                $errors = libxml_get_errors();
                                libxml_clear_errors();
                                if(empty($errors)) {
                                    echo highlight_string($log->response);
                                } else {
                                    echo '<pre style="background:#18171B; color: white; max-height: 500px;overflow-y:scroll">'.$log->response.'</pre>';
                                }
                            }
                            ?>
                        @else
                            <pre style="background:#18171B; color: white; max-height: 500px;overflow-y:scroll">{{$log->response}}</pre>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="response_headers_{{$key}}">
                        @dump(json_decode($log->response_headers, true))
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <h5>
              No Records
            </h5>
          @endforelse

        </div>
        @if(config('apilog.driver') === 'db')
            <div class="d-flex justify-content-center">
                {!! $apilogs->links() !!}
            </div>
        @endif
    </main>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

