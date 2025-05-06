@extends('dashboard.layout')
@section('content')
    <div class="card">
        <div class="card-body">
            <div id="qr-reader" style="width: auto; margin: 0 auto;"></div>

            <div class="mt-3">
                <h1 class="card-title">Hasil : </h1>
                <h3 class="card-text" id="scannerResult">Silahkah scan terlebih dahulu</h3>
            </div>


            <audio id="myAudio">
                <source src="{{ asset('sounds/qrcode.mp3') }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        var x = document.getElementById("myAudio");

        function playAudio() {
            x.play();
        }

        function onScanSuccess(decodedText, decodedResult) {
            scannerResult = decodedText;

            $.ajax({
                url: '/penjemput-datang',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    data: scannerResult
                },
                success: function(response) {
                    console.log(response);
                    playAudio();
                    $('#scannerResult').html(response['data'] + '<br> time: ' + response['time']);
                }
            });
        }
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 24,
                qrbox: {
                    width: 200,
                    height: 200,
                },
                rememberLastUsedCamera: true,
                showTorchButtonIfSupported: true
            });

        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endpush
