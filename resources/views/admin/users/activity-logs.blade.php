@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-sm-6">
                <h4 class="page-title">Activity Logs</h4>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table id="logsTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sample Static Logs --}}
                        <tr>
                            <td>2025-05-07 08:23:14</td>
                            <td>Admin</td>
                            <td><span class="badge bg-success">Create</span></td>
                            <td>Created a new user account for Jane Doe</td>
                        </tr>
                        <tr>
                            <td>2025-05-07 09:02:45</td>
                            <td>John Smith</td>
                            <td><span class="badge bg-info">Update</span></td>
                            <td>Updated role permissions for "User"</td>
                        </tr>
                        <tr>
                            <td>2025-05-07 10:15:30</td>
                            <td>Admin</td>
                            <td><span class="badge bg-danger">Delete</span></td>
                            <td>Deleted role "Manager"</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    $(function () {
        $('#logsTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']],
            columnDefs: [
                { targets: 2, orderable: false }
            ]
        });
    });
</script>
@endsection
