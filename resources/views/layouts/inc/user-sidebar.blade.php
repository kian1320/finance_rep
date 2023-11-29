<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Welcome</div>
                <a class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}"
                    href="{{ url('user/dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link {{ Request::is('user/add-ysummary') ? 'active' : '' }}"
                    href="{{ url('user/add-ysummary') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Add Yearly Budget
                </a>

                <hr>

                <!-- Receipt Types -->
                <a class="nav-link {{ Request::is('user/btypes') || Request::is('user/add-btypes') || Request::segment(2) === 'edit-btypes' ? 'collapsed' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsBudgetTypes"
                    aria-expanded="false" aria-controls="collapseLayoutsBudgetTypes">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Receipt Types
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/btypes') || Request::is('user/add-btypes') || Request::segment(2) === 'edit-btypes' ? 'show' : '' }}"
                    id="collapseLayoutsBudgetTypes" aria-labelledby="headingBudgetTypes"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/add-btypes') ? 'active' : '' }}"
                            href="{{ url('user/add-btypes') }}">Add Receipt Type</a>
                        <a class="nav-link {{ Request::is('user/btypes') || Request::is('user/edit-btypes/*') || Request::segment(2) === 'edit-btypes' ? 'active' : '' }}"
                            href="{{ url('user/btypes') }}">View Receipt Types</a>
                    </nav>
                </div>



                <!-- Expenses Types -->
                <a class="nav-link {{ Request::is('user/types') || Request::is('user/add-types') || Request::segment(2) === 'stypes' || Request::segment(2) === 'edit-types' ? 'collapsed' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsExpensesTypes"
                    aria-expanded="false" aria-controls="collapseLayoutsExpensesTypes">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Expenses Types
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/types') || Request::is('user/add-types') || Request::segment(2) === 'stypes' || Request::segment(2) === 'edit-types' ? 'show' : '' }}"
                    id="collapseLayoutsExpensesTypes" aria-labelledby="headingExpensesTypes"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/add-types') ? 'active' : '' }}"
                            href="{{ url('user/add-types') }}">Add Expenses Type</a>
                        <a class="nav-link {{ Request::is('user/types') || Request::is('user/edit-types/*') || Request::is('user/stypes/*') ? 'active' : '' }}"
                            href="{{ url('user/types') }}">View Expenses Types</a>
                    </nav>
                </div>

                <hr>

                <!-- Add Bank -->
                <a class="nav-link {{ Request::is('user/bank') || Request::is('user/add-bank') || Request::segment(2) === 'edit-bank' ? 'collapsed' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsBank" aria-expanded="false"
                    aria-controls="collapseLayoutsBank">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Add Bank
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/bank') || Request::is('user/add-bank') || Request::segment(2) === 'edit-expenses' ? 'show' : '' }}"
                    id="collapseLayoutsBank" aria-labelledby="headingBank" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/add-bank') ? 'active' : '' }}"
                            href="{{ url('user/add-bank') }}">Add Bank</a>
                        <a class="nav-link {{ Request::is('user/bank') || Request::is('user/edit-bank/*') || Request::segment(2) === 'edit-bank' ? 'active' : '' }}"
                            href="{{ url('user/bank') }}">View Banks</a>
                    </nav>
                </div>

                <hr>

                {{-- <a class="nav-link {{ Request::is('user/add-summary') ? 'active' : '' }}"
                    href="{{ url('user/add-summary') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Monthly Receipt
                </a>

                <a class="nav-link {{ Request::is('user/budget') ? 'active' : '' }}" href="{{ url('user/budget') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Edit Monthly Receipt
                </a> --}}


                <!-- Monthly Expenses -->
                <a class="nav-link {{ Request::is('user/budget', 'user/add-summary', 'user/edit-budget/*', 'user/add-summary') ? 'collapsed' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsReceipt"
                    aria-expanded="false" aria-controls="collapseLayoutsReceipt">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Monthly Receipt
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/budget', 'user/add-summary', 'user/edit-budget/*', 'user/add-summary', 'user/budget', 'user/edit-budget/*') ? 'show' : '' }}"
                    id="collapseLayoutsReceipt" aria-labelledby="headingExpenses" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/add-summary') ? 'active' : '' }}"
                            href="{{ url('user/add-summary') }}">Add Receipt</a>
                        <a class="nav-link {{ Request::is('user/budget', 'user/edit-expenses/*', 'user/edit-bank/*', 'user/edit-budget/*') ? 'active' : '' }}"
                            href="{{ url('user/budget') }}">View / Edit Receipt</a>
                    </nav>
                </div>







                <!-- Monthly Reciept -->
                <a class="nav-link {{ Request::is('user/expenses') || Request::is('user/add-expenses') || Request::segment(2) === 'edit-expenses' ? 'collapsed' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsExpenses"
                    aria-expanded="false" aria-controls="collapseLayoutsExpenses">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Monthly Expenses
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/expenses') || Request::is('user/add-expenses') || Request::segment(2) === 'edit-expenses' ? 'show' : '' }}"
                    id="collapseLayoutsExpenses" aria-labelledby="headingExpenses"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/add-expenses') ? 'active' : '' }}"
                            href="{{ url('user/add-expenses') }}">Add Expenses</a>
                        <a class="nav-link {{ Request::is('user/expenses') || Request::is('user/edit-expenses/*') || Request::segment(2) === 'edit-expenses' ? 'active' : '' }}"
                            href="{{ url('user/expenses') }}">View / Edit Expenses</a>
                    </nav>
                </div>

                <!-- Late Expenses -->
                <a class="nav-link {{ Request::is('user/lexpenses') || Request::is('user/add-lexpenses') ? 'collapsed' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsLateExpenses"
                    aria-expanded="false" aria-controls="collapseLayoutsLateExpenses">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Late Expenses
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/lexpenses') || Request::is('user/add-lexpenses') ? 'show' : '' }}"
                    id="collapseLayoutsLateExpenses" aria-labelledby="headingLateExpenses"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/add-lexpenses') ? 'active' : '' }}"
                            href="{{ url('user/add-lexpenses') }}">Add Late Expenses</a>
                        <a class="nav-link {{ Request::is('user/lexpenses') || Request::is('user/edit-lexpenses/*') ? 'active' : '' }}"
                            href="{{ url('user/lexpenses') }}">View Late Expenses</a>
                    </nav>
                </div>

                <hr>

                <!-- Summary -->
                <a class="nav-link {{ Request::is('user/summary') || Request::is('user/ysummary') ? 'collapse show' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsySummary"
                    aria-expanded="false" aria-controls="collapseLayoutsySummary">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Summary
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/ysummary') || Request::is('user/summary') ? 'show' : '' }}"
                    id="collapseLayoutsySummary" aria-labelledby="headingSummary" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/summary') ? 'active' : '' }}"
                            href="{{ url('user/summary') . '?year=' . date('Y') }}">Monthly Summary</a>
                        <a class="nav-link {{ Request::is('user/ysummary') ? 'active' : '' }}"
                            href="{{ url('user/ysummary') }}">View Yearly Summary</a>
                    </nav>
                </div>


                {{-- <!-- view the budgets list -->
                <a class="nav-link {{ Request::is('user/summary') || Request::is('user/ysummary') ? 'collapse show' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsySummary"
                    aria-expanded="false" aria-controls="collapseLayoutsySummary">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Receipt/Summary
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('user/budget') || Request::is('user/budget') ? 'show' : '' }}"
                    id="collapseLayoutsySummary" aria-labelledby="headingSummary" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::is('user/budget') ? 'active' : '' }}"
                            href="{{ url('user/budget') }}">Edit Receipt List</a>
                        <a class="nav-link {{ Request::is('user/ysummary') ? 'active' : '' }}"
                            href="{{ url('user/ysummary') }}">View Yearly Summary</a>
                    </nav>
                </div> --}}





            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as: {{ strtoupper(Auth::user()->name) }}</div>
        </div>
    </nav>
</div>
