{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>QrCode</title>
</head>

<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-2">
                <p class="mb-0">Simple</p>
                <a href="" id="container">{!! $simple !!}</a><br />
                <button id="download" class="mt-2 btn btn-info text-light" onclick="downloadSVG()">Download
                    SVG</button>
            </div>
            <div class="col-md-2">
                <p class="mb-0">Color Change</p>
                {!! $changeColor !!}
            </div>
            <div class="col-md-2">
                <p class="mb-0">Background Color Change </p>
                {!! $changeBgColor !!}
            </div>


            <div class="col-md-2">
                <p class="mb-0">Style Square</p>
                {!! $styleSquare !!}
            </div>
            <div class="col-md-2">
                <p class="mb-0">Style Dot</p>
                {!! $styleDot !!}
            </div>
            <div class="col-md-2">
                <p class="mb-0">Style Round</p>
                {!! $styleRound !!}
            </div>
        </div>
    </div>
</body>

</html> --}}


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}

</head>

<body>
    <div style="display:none;">
        <img id="svg-image" src="data:image/svg+xml;base64,{{ $base64Svg }}" />
        <canvas id="canvas"></canvas>
    </div>

    <script>
        window.onload = () => {
            const img = document.getElementById('svg-image');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);

                const pngUrl = canvas.toDataURL('image/png');

                const a = document.createElement('a');
                a.href = pngUrl;
                a.download = 'qrcode.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            };
        };
    </script>
</body>

</html>
