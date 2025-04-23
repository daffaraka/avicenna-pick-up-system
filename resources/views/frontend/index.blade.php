<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Avicenna Pick Up System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>
    <div class="container py-5">
        <h1>Avicenna Pick Up System</h1>

        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <form action="{{route('post.store')}}" method="POST">
            @csrf
            <div class="form-group mb-2">
                <label for="">Nama Penjemput</label>
                <input type="text" class="form-control" name="post">
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
