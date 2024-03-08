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
        <div class="container">
            <div class="header mt-2">
                <div class="row">
                    <div class="col-8">
                        <h3>Excel File Uploader</h3>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-8">
                        @if(Session::has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            
                        </div>
                        @endif
                        <form action="{{ route('postUpload') }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="excel-file" class="form-label">Excel File</label>
                                <input class="form-control" type="file" id="excel-file" name="excel-file">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mt-3">
                            <h4>Uploaded File Status</h4>

                            <table class="table table-bordered">
                                <thead>
                                    <tr class="table-active">
                                        <th>Id</th>
                                        <th>Description</th>
                                        <th>File name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Errors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($uploadedFiles))
                                        @foreach($uploadedFiles as $file)
                                        <tr>
                                            <td>{{ $file->id }}</td>
                                            <td>{{ $file->description }}</td>
                                            <td>{{ $file->file_name }}</td>
                                            <td>{{ $file->extention }}</td>
                                            <td>{{ $file->status }}</td>
                                            <td>{{ $file->created_at }}</td>
                                            <td>{{ $file->updated_at }}</td>
                                            <td>{{ $file->errors }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>