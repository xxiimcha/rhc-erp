@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fs-16 fw-semibold mb-1 mb-md-2">Good Morning, <span class="text-primary">Admin!</span></h4>
                <p class="text-muted mb-0">Here's what's happening with your store today.</p>
            </div>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">RHC ERP</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Additional dashboard widgets, charts, cards, etc. can be added below -->
@endsection
