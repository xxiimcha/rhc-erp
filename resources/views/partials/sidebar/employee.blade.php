<li class="menu-title">Employee Panel</li>

<li>
    <a href="{{ url('employee/profile') }}">
        <i class="fas fa-user"></i>
        <span>My Profile</span>
    </a>
</li>

<li>
    <a href="{{ url('employee/attendance') }}">
        <i class="fas fa-calendar-check"></i>
        <span>Attendance Logs</span>
    </a>
</li>

<li>
    <a href="{{ url('employee/clocking') }}">
        <i class="fas fa-clock"></i>
        <span>Time In / Out</span>
    </a>
</li>

<li>
    <a href="{{ url('employee/leaves') }}">
        <i class="fas fa-plane-departure"></i>
        <span>My Leaves</span>
    </a>
</li>

<li>
    <a href="{{ url('employee/payroll') }}">
        <i class="fas fa-money-check-alt"></i>
        <span>Payroll</span>
    </a>
</li>

<li>
    <a href="{{ url('employee/documents') }}">
        <i class="fas fa-file-alt"></i>
        <span>My Documents</span>
    </a>
</li>
