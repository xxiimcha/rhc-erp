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

                <li class="menu-title">Human Resources</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-user-friends"></i>
                        <span>HR Module</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/hr/employees') }}">Employee Records</a></li>
                        <li><a href="{{ url('admin/hr/attendance') }}">Attendance Tracking</a></li>
                        <li><a href="{{ url('admin/hr/leaves') }}">Leave Management</a></li>
                        <li><a href="{{ url('admin/hr/payroll') }}">Payroll</a></li>
                        <li><a href="{{ url('admin/hr/recruitment') }}">Recruitment</a></li>
                    </ul>
                </li>

                <li class="menu-title">Finance & Accounting</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-coins"></i>
                        <span>Finance Module</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/finance/expenses') }}">Expenses</a></li>
                        <li><a href="{{ url('admin/finance/payroll') }}">Payroll</a></li>
                        <li><a href="{{ url('admin/finance/invoices') }}">Invoices</a></li>
                        <li><a href="{{ url('admin/finance/reports') }}">Financial Reports</a></li>
                    </ul>
                </li>

                <li class="menu-title">IT & Support</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-tools"></i>
                        <span>IT Management</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/it/assets') }}">IT Assets</a></li>
                        <li><a href="{{ url('admin/it/tickets') }}">Support Tickets</a></li>
                        <li><a href="{{ url('admin/it/logs') }}">System Logs</a></li>
                    </ul>
                </li>

                <li class="menu-title">Project & Task Management</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-project-diagram"></i>
                        <span>Projects</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/projects') }}">All Projects</a></li>
                        <li><a href="{{ url('admin/projects/create') }}">Create Project</a></li>
                        <li><a href="{{ url('admin/tasks') }}">Task Assignments</a></li>
                        <li><a href="{{ url('admin/tasks/progress') }}">Progress Tracking</a></li>
                    </ul>
                </li>

                <li class="menu-title">Administrative Tools</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-cogs"></i>
                        <span>Admin Tools</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/admin/announcements') }}">Announcements</a></li>
                        <li><a href="{{ url('admin/admin/documents') }}">Document Management</a></li>
                        <li><a href="{{ url('admin/admin/communications') }}">Internal Communications</a></li>
                    </ul>
                </li>

                <li class="menu-title">Inventory & Procurement</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-boxes"></i>
                        <span>Inventory</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/inventory/stocks') }}">Stock Inventory</a></li>
                        <li><a href="{{ url('admin/inventory/requests') }}">Purchase Requests</a></li>
                        <li><a href="{{ url('admin/inventory/suppliers') }}">Suppliers</a></li>
                        <li><a href="{{ url('admin/inventory/approvals') }}">Procurement Approvals</a></li>
                    </ul>
                </li>

                <li class="menu-title">Customer Relations (CRM)</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-handshake"></i>
                        <span>CRM</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/crm/clients') }}">Client Information</a></li>
                        <li><a href="{{ url('admin/crm/leads') }}">Leads</a></li>
                        <li><a href="{{ url('admin/crm/interactions') }}">Interactions</a></li>
                        <li><a href="{{ url('admin/crm/campaigns') }}">Marketing Campaigns</a></li>
                    </ul>
                </li>

                <li class="menu-title">Compliance & Legal</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-shield-alt"></i>
                        <span>Compliance</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/compliance/policies') }}">Policies</a></li>
                        <li><a href="{{ url('admin/compliance/incidents') }}">Legal Incidents</a></li>
                        <li><a href="{{ url('admin/compliance/contracts') }}">Contracts</a></li>
                    </ul>
                </li>

                <li class="menu-title">Franchise Management</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-store-alt"></i>
                        <span>Franchise</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/franchise/list') }}">Franchisee List</a></li>
                        <li><a href="{{ url('admin/franchise/contracts') }}">Contracts</a></li>
                        <li><a href="{{ url('admin/franchise/evaluations') }}">Evaluations</a></li>
                        <li><a href="{{ url('admin/franchise/applications') }}">Applications</a></li>
                    </ul>
                </li>

                <li class="menu-title">User Management & Roles</li>
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="fas fa-users-cog"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ url('admin/users') }}">User Accounts</a></li>
                        <li><a href="{{ url('admin/users/roles') }}">Roles & Permissions</a></li>
                        <li><a href="{{ url('admin/users/activity') }}">Activity Logs</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
