<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KRA Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row my-4">
            <form method="POST" action="{{ url('kra-test') }}">
                @csrf
                <div class="form-group">
                  <label for="exampleFormControlInput1">Endpoint</label>
                  <input type="text" class="form-control" id="endpoint" placeholder="Provide endpoint here" name="endpoint" value="{{ $url ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Token</label>
                    <input type="text" class="form-control" id="token" placeholder="Provide token here like ZxZoaZMUQbUJDljA7kTExQ==2023" name="token" value="{{ $token ?? '' }}">
                </div>
                <div class="form-group">
                  <label for="exampleFormControlTextarea1">Payload</label>
                  <textarea class="form-control" id="payload" rows="3" name="payload" required> {{ $payload ?? '' }} </textarea>
                </div>
                <button type="submit" class="btn btn-primary my-4">Submit</button>
            </form>
        </div>
        @if (! empty($response))
            <div class="row my-4">
                {{ $response  }}
            </div>
        @elseif ($response == false && gettype($response) != 'NULL')
            <pre>Request timed out</pre>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>