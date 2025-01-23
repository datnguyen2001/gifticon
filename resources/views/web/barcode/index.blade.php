@extends('web.index')
@section('title','Barcode')

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/barcode.css')}}">
@stop
{{--content of page--}}
@section('content')
    @include('web.partials.success-alert')
    @include('web.partials.failed-alert')
    <form method="POST" action="{{route('barcode.scan')}}" id="barcode-form">
        @csrf
        <input type="text" name="barcode" id="barcode" placeholder="Nhập mã vạch" class="form-control" autofocus/>
    </form>
    <div id="product-container"></div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/barcode.js')}}"></script>
    <script>
        document.addEventListener('click', function() {
            document.getElementById('barcode').focus();
        });

        document.getElementById('barcode-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const barcode = document.getElementById('barcode').value;

            fetch('{{route('barcode.scan')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ barcode: barcode })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showSuccessAlert(data.message);
                    } else {
                        showFailedAlert(data.message);
                    }
                    document.getElementById('product-container').innerHTML = data.html;
                    document.getElementById('barcode').value = '';
                })
                .catch(error => console.error('Error:', error));
        });

        function showSuccessAlert(message) {
            $(".success-message").text(message);
            $(".success-overlay").fadeIn();
            $(".success-alert").fadeIn();
        }

        function showFailedAlert(message) {
            $(".failed-message").text(message);
            $(".failed-overlay").fadeIn();
            $(".failed-alert").fadeIn();
        }

        function hidePopup() {
            $(".success-overlay").fadeOut();
            $(".success-alert").fadeOut();
            $(".failed-overlay").fadeOut();
            $(".failed-alert").fadeOut();
        }

        $(".close-popup").on("click", function () {
            hidePopup();
        });

        $(".success-overlay").on("click", function () {
            hidePopup();
        });

        $(".failed-overlay").on("click", function () {
            hidePopup();
        });
    </script>
@stop
