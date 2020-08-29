<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Import</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="container">
        <div class="card bg-light mt-3">
            <div class="card-header">
                Import Products Excel
            </div>
            <div class="card-body">
                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button class="btn btn-success">Import Data</button>
                </form>

                @if(session('status'))
                    <div class="alert alert-success alert-block">
                        <strong>{{ session('status') }}</strong>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        Upload Error<br><br>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('failures'))
                    <table class="table table-warning">
                        <tr>
                            <th>Row</th>
                            <th>Errors</th>
                            <th>Value</th>
                        </tr>
                        @foreach(session('failures') as $failure)
                        <tr>
                            <td>{{$failure->row()}}</td>
                            <td>
                                <ul>
                                    @foreach($failure->errors() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{$failure->values()[$failure->attribute()]}}</td>
                        </tr>
                        @endforeach
                    </table>
                @endif

            </div>
        </div>
    </div>
</div>
</body>
</html>
