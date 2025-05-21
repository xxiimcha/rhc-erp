@extends('layouts.admin')

@section('title', 'Time In / Out')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-12">
                <h4 class="page-title">Self Time In / Out</h4>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body text-center">
                <p class="lead">Current Time: <span id="clock" class="fw-bold"></span></p>

                <div class="mb-3">
                    <video id="video" width="300" height="225" autoplay class="border rounded"></video>
                    <canvas id="canvas" width="300" height="225" class="d-none"></canvas>
                </div>

                <form method="POST" action="{{ route('employee.clocking.store') }}" onsubmit="return capturePhoto();">
                    @csrf
                    <input type="hidden" name="image" id="imageInput">
                    <button type="submit" class="btn btn-success">Submit Time Log</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const imageInput = document.getElementById('imageInput');

    // Update clock every second
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').innerText = now.toLocaleString();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Get webcam stream
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Camera access error:", err);
            alert("Camera access is required to use the clocking system.");
        });

    // Capture photo before form submission
    function capturePhoto() {
        console.log('Capturing photo...');
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/jpeg');
        imageInput.value = dataURL;

        if (!dataURL || dataURL.length < 100) {
            alert("Failed to capture photo. Please try again.");
            return false;
        }

        return true;
    }
</script>
@endsection
