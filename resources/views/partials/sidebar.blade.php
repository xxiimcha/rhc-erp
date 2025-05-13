@php
    $user = Auth::user();
@endphp

<div class="sidebar-left">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="left-menu list-unstyled" id="side-menu">

                <li>
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard & Analytics</span>
                    </a>
                </li>

                @if($user->role === 'system_admin')

                    {{-- Show all sections if user is system_admin --}}
                    @include('partials.sidebar.hr')
                    @include('partials.sidebar.finance')
                    @include('partials.sidebar.it')
                    @include('partials.sidebar.projects')
                    @include('partials.sidebar.admin_tools')
                    @include('partials.sidebar.inventory')
                    @include('partials.sidebar.crm')
                    @include('partials.sidebar.compliance')
                    @include('partials.sidebar.franchise')
                    @include('partials.sidebar.users')
                    @include('partials.sidebar.settings')

                @else

                    {{-- Show sections based on department --}}
                    @if($user->department === 'HR')
                        @include('partials.sidebar.hr')
                    @endif

                    @if($user->department === 'Accounting')
                        @include('partials.sidebar.finance')
                    @endif

                    @if($user->department === 'MIS')
                        @include('partials.sidebar.it')
                        @include('partials.sidebar.users')
                    @endif

                    @if($user->department === 'Project Management')
                        @include('partials.sidebar.projects')
                    @endif

                    @if($user->department === 'Administration')
                        @include('partials.sidebar.admin_tools')
                    @endif

                    @if($user->department === 'Inventory')
                        @include('partials.sidebar.inventory')
                    @endif

                    @if($user->department === 'CRM')
                        @include('partials.sidebar.crm')
                    @endif

                    @if($user->department === 'Legal')
                        @include('partials.sidebar.compliance')
                    @endif

                    @if($user->department === 'Franchise')
                        @include('partials.sidebar.franchise')
                    @endif

                @endif

            </ul>
        </div>
    </div>
</div>
