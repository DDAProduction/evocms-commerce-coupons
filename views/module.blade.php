<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('EvocmsDiscounts::main.caption')</title>

    @foreach($css as $file)
        <link rel="stylesheet" href="/{{ $file }}?v=<?= file_exists(MODX_BASE_PATH.$file)?filemtime(MODX_BASE_PATH.$file):'' ?>">
    @endforeach


</head>
<body>

<script>
    var appConfig = {

        moduleUrl: '{!! $moduleUrl !!}',
        lang: {!! json_encode($lang) !!}

    };
    var lang =  {!! json_encode($lang) !!}
</script>

<div id="app"></div>
<div id="pager"></div>


@foreach($js as $file)
    <script src="/{{ $file }}?v=<?= file_exists(MODX_BASE_PATH.$file)?filemtime(MODX_BASE_PATH.$file):'' ?>"></script>
@endforeach


</body>
</html>