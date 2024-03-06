<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Excel File Uploader</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

        <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
        
    </head>
    <body class="">
        <div class="header">
            <h2>Excel File Uploader</h2>
        </div>
        <div class="content">
            @if ($errors->any())
            <div class="notification is-danger is-light">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('postUpload') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <textarea name="description" placeholder="Description"></textarea>
                <input type="file" name="excel-file">
                <input type="submit" value="Submit">
            </form>
            <div style="display: inline-block;">
            @if(Session::has('message'))
                {{ Session::get('message') }}
            @endif
            </div>
        </div>
    </body>
</html>