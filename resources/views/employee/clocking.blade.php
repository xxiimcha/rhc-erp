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

                @if ($hasCompletedToday)
                    <div class="alert alert-info">
                        You have completed your time-in and time-out for today. Please come back tomorrow.
                    </div>
                    <button class="btn btn-secondary" disabled>Time Log Locked</button>
                @else
                    <form method="POST" action="{{ route('employee.clocking.store') }}" id="clockingForm">
                        @csrf
                        <input type="hidden" name="image" id="imageInput">
                        <button type="button" class="btn btn-success" onclick="captureAndPreview()">Submit Time Log</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Time Log</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>This photo will be used to log your Time In/Out.</p>
                <img id="previewImage" src="" class="img-thumbnail mb-3" width="300" alt="Preview">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmitBtn">Confirm & Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    const form = document.getElementById('clockingForm');

    function updateClock() {
        const now = new Date();
        document.getElementById('clock').innerText = now.toLocaleString();
    }
    setInterval(updateClock, 1000);
    updateClock();

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Camera access error:", err);
            alert("Camera access is required to use the clocking system.");
        });

    function captureAndPreview() {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/jpeg');
        imageInput.value = dataURL;

        if (!dataURL || dataURL.length < 100) {
            alert("Failed to capture photo. Please try again.");
            return false;
        }

        previewImage.src = dataURL;
        confirmModal.show();
    }

    confirmSubmitBtn.addEventListener('click', () => {
        confirmModal.hide();
        form.submit();
    });
</script>
@endsection
