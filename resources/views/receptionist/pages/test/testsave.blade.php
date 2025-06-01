{{-- resources/views/receptionist/pages/test_entry.blade.php --}}
@extends('receptionist.layouts.app')

@section('content')
    <!-- Bootstrap Icons & SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* Container and Card Styles */
        .form-card {
            border: 1.5px solid #d2e3fc;
            border-radius: 14px;
            box-shadow: 0 2px 14px rgba(0, 105, 255, .06);
            background: #fff;
            margin-bottom: 32px;
        }
        .form-section-title {
            font-weight: 700;
            color: #1976d2;
            font-size: 1.28rem;
            margin-bottom: 12px;
            letter-spacing: .5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        /* Tab Navigation Styles */
        .nav-tabs .nav-link {
            font-weight: 600;
            font-size: 1.06rem;
            letter-spacing: .5px;
            color: #444;
            background: #f4f6fa;
            border-radius: 8px 8px 0 0;
            margin-right: 8px;
            transition: background 0.3s;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background: #fff;
            border-bottom: 2.5px solid #0d6efd;
        }
        /* Form Control Focus */
        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.09rem rgba(121, 182, 255, 0.35);
        }
        /* Tab Pane Fade-in */
        .tab-pane {
            animation: fadeIn 0.4s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        /* Alerts */
        .alert {
            border-radius: 8px;
        }
    </style>

    <div class="container py-4">
        {{-- Page Header --}}
        <div class="mx-auto mb-4" style="max-width: 700px;">
            <h2 class="fw-bold text-center mb-1" style="letter-spacing:1px;">
                <i class="bi bi-clipboard-data text-primary me-2"></i>
                New Lab Entry
            </h2>
            <p class="text-center text-muted mb-4">Fill in the details below to add a new patient and tests.</p>
        </div>

        {{-- Card Container --}}
        <div class="card form-card mx-auto" style="max-width: 820px;">
            <div class="card-body">

                {{-- Success / Error Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                        <div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Form Begins --}}
                <form action="{{ route('testsave.store') }}" method="POST" id="mainForm">
                    @csrf

                    {{-- TABS NAV --}}
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                                    type="button" role="tab" aria-controls="personal" aria-selected="true">
                                <i class="bi bi-person-circle me-1"></i> Personal Info
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="test-tab" data-bs-toggle="tab" data-bs-target="#test" type="button"
                                    role="tab" aria-controls="test" aria-selected="false">
                                <i class="bi bi-activity me-1"></i> Test Info
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="referral-tab" data-bs-toggle="tab" data-bs-target="#referral"
                                    type="button" role="tab" aria-controls="referral" aria-selected="false">
                                <i class="bi bi-person-badge me-1"></i> Referral
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="comment-tab" data-bs-toggle="tab" data-bs-target="#comment" type="button"
                                    role="tab" aria-controls="comment" aria-selected="false">
                                <i class="bi bi-chat-left-text me-1"></i> Comment
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button"
                                    role="tab" aria-controls="payment" aria-selected="false">
                                <i class="bi bi-cash-coin me-1"></i> Payment
                            </button>
                        </li>
                    </ul>

                    {{-- TABS CONTENT --}}
                    <div class="tab-content" id="myTabContent">
                        {{-- PERSONAL INFO TAB --}}
                        <div class="tab-pane fade show active p-2" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                            <div class="form-section-title mb-3">
                                <i class="bi bi-person-lines-fill"></i> Personal Info
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="user_name" id="generatedUsername" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="password" id="generatedPassword" readonly>
                                        <button type="button" class="btn btn-outline-primary" id="changePasswordBtn">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Relation</label>
                                    <select name="relation" class="form-select">
                                        <option value="Self" selected>Self</option>
                                        <option value="Father">Father</option>
                                        <option value="Brother">Brother</option>
                                        <option value="Sister">Sister</option>
                                        <option value="Son">Son</option>
                                        <option value="Daughter">Daughter</option>
                                        <option value="Mother">Mother</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Title</label>
                                    <select name="title" class="form-select" id="titleSelect">
                                        <option value="">-- Select Title --</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" id="genderSelect">
                                        <option value="">-- Select --</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="transgender">Transgender</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="nameInput" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="example@domain.com">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+92</span>
                                        <input type="tel" class="form-control" name="phone" id="phonePersonal"
                                               pattern="3[0-9]{9}" maxlength="10" placeholder="3001234567"
                                               title="Enter a valid Pakistani mobile number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Age</label>
                                    <input type="number" class="form-control" name="age" min="0" placeholder="e.g. 30">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary px-4" onclick="nextTab()">
                                    Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- TEST INFO TAB --}}
                        <div class="tab-pane fade p-2" id="test" role="tabpanel" aria-labelledby="test-tab">
                            <div class="form-section-title mb-3">
                                <i class="bi bi-activity"></i> Test Info
                            </div>
                            <div class="row align-items-end mb-3">
                                <div class="col-md-4">
                                    <label for="catFilter" class="form-label">Filter by Category:</label>
                                    <select id="catFilter" class="form-select">
                                        <option value="all">All</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ strtolower($cat->testCat) }}">{{ $cat->testCat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8 text-end">
                                    <small class="text-muted">Select multiple tests below:</small>
                                </div>
                            </div>
                            <div class="table-responsive" style="max-height: 240px;">
                                <table class="table table-bordered table-striped" id="testsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Select</th>
                                            <th>Test Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($availableTests as $test)
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <input type="checkbox" class="form-check-input test-check"
                                                           data-addTestId="{{ $test->addTestId }}"
                                                           data-testName="{{ $test->testName }}"
                                                           data-testCatId="{{ $test->testCatId }}"
                                                           data-testCost="{{ $test->testCost }}">
                                                </td>
                                                <td class="align-middle">{{ $test->testName }}</td>
                                                <td class="align-middle testCatCell">
                                                    {{ $test->category ? $test->category->testCat : $test->testCatId }}
                                                </td>
                                                <td class="align-middle">{{ $test->testCost }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="tests" id="testsJson">
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="previousTab()">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" onclick="nextTab()">
                                    Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- REFERRAL TAB --}}
                        <div class="tab-pane fade p-2" id="referral" role="tabpanel" aria-labelledby="referral-tab">
                            <div class="form-section-title mb-3">
                                <i class="bi bi-person-badge"></i> Referral
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Select Panel/Referrer</label>
                                <select class="form-select" name="referralType" id="referralType">
                                    <option value="">-- Select Panel --</option>
                                    <option value="normal">Normal/Loyalty Card</option>
                                    <option value="staff">Staff Panel</option>
                                    <option value="external">External Panel</option>
                                    <option value="referrer">Referrer/Loyalty Card</option>
                                </select>
                            </div>

                            {{-- STAFF PANEL SECTION --}}
                            <div id="staffPanelSection" style="display: none;">
                                <h5 class="mb-2">Staff Panel List</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Select</th>
                                                <th>Staff Name</th>
                                                <th>Credits</th>
                                                <th>Remaining Credits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($staffList as $staff)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <input type="radio" name="staffPanelId"
                                                               value="{{ $staff->staffPanelId }}"
                                                               data-remaining="{{ $staff->remainingCredits }}"
                                                               data-staff-name="{{ $staff->user->name ?? 'Unknown' }}">
                                                    </td>
                                                    <td class="align-middle">{{ $staff->user->name ?? 'Unknown' }}</td>
                                                    <td class="align-middle">{{ $staff->credits }}</td>
                                                    <td class="align-middle">{{ $staff->remainingCredits }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- EXTERNAL PANEL SECTION --}}
                            <div id="externalPanelSection" style="display: none;">
                                <h5 class="mb-2">External Panel List</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Select</th>
                                                <th>Panel Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($externalList as $ext)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <input type="radio" name="externalPanelId"
                                                               value="{{ $ext->extPanelId }}"
                                                               data-remaining="{{ $ext->remainingCredits }}"
                                                               data-panel-name="{{ $ext->panelName }}">
                                                    </td>
                                                    <td class="align-middle">{{ $ext->panelName }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- REFERRER SECTION --}}
                            <div id="referrerSection" style="display: none;">
                                <h5 class="mb-2">Referrer List</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Select</th>
                                                <th>Referrer ID</th>
                                                <th>Referrer Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($referrerList as $ref)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <input type="radio" name="id"
                                                               value="{{ $ref->id }}"
                                                               data-referrer-name="{{ $ref->referrerName }}">
                                                    </td>
                                                    <td class="align-middle">{{ $ref->id }}</td>
                                                    <td class="align-middle">{{ $ref->referrerName }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="previousTab()">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" onclick="nextTab()">
                                    Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- COMMENT TAB --}}
                        <div class="tab-pane fade p-2" id="comment" role="tabpanel" aria-labelledby="comment-tab">
                            <div class="form-section-title mb-3">
                                <i class="bi bi-chat-left-text"></i> Add Comment
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comment Description</label>
                                <textarea class="form-control" name="comment" rows="3" placeholder="Enter any remarks..."></textarea>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="previousTab()">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" onclick="nextTab()">
                                    Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                </button>
                            </div>
                        </div>

                        {{-- PAYMENT TAB --}}
                        <div class="tab-pane fade p-2" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            <div class="form-section-title mb-3">
                                <i class="bi bi-cash-coin"></i> Payment
                            </div>
                            @foreach ($loyaltyCards as $loyaltyCard)
                                <div data-phone="{{ $loyaltyCard->phone_number }}"
                                     data-percentage="{{ $loyaltyCard->percentage }}" style="display:none;">
                                    {{ $loyaltyCard->phone_number }}
                                </div>
                            @endforeach

                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="paymentPhone" readonly>
                            </div>

                            <div id="panelInfoSection" style="display: none;">
                                <p><strong>Panel Type:</strong> <span id="panelTypeLabel"></span></p>
                                <p><strong>Panel Name:</strong> <span id="panelNameLabel"></span></p>
                                <p><strong>Staff Remaining Credits:</strong> <span id="staffRemainingLabel"></span></p>
                                <p><strong>External Discount:</strong> <span id="externalDiscountLabel"></span></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Net Cost (before discount)</label>
                                <input type="number" class="form-control" name="netCost" id="netCost" value="0" readonly>
                            </div>
                            <div class="mb-3" id="panelDiscountContainer">
                                <label class="form-label">Panel / Staff Discount (if any)</label>
                                <input type="number" class="form-control" name="panelDiscount" id="panelDiscount" value="0" readonly>
                            </div>
                            <div class="mb-3" id="loyaltyDiscountContainerPercent">
                                <label class="form-label">Loyalty Discount (%)</label>
                                <input type="text" class="form-control" id="loyaltyDiscountPercent" value="0" readonly>
                            </div>
                            <div class="mb-3" id="loyaltyDiscountContainerAmount">
                                <label class="form-label">Loyalty Discount Amount</label>
                                <input type="text" class="form-control" id="loyaltyDiscountAmount" value="0" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Net Payable</label>
                                <input type="number" class="form-control" name="netPayable" id="netPayable" value="0" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Received Amount</label>
                                <input type="number" class="form-control" name="recieved" step="0.01" id="receivedInput" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pending Amount</label>
                                <input type="number" class="form-control" name="pending" step="0.01" id="pendingInput" value="0" readonly>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="previousTab()">
                                    <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                </button>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check2-circle me-1"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- End Form --}}
            </div>
        </div>
    </div>

    {{-- Custom Modal for Username Copy --}}
    <style>
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
            animation: fadeInModal 0.5s;
        }
        .custom-modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            animation: slideInModal 0.3s ease-out;
        }
        .close-btn { color: #aaa; float:right; font-size:28px; cursor:pointer; }
        #modalCloseBtn {
            margin-top: 10px; padding:8px 16px;
            background-color: #4CAF50; color: #fff;
            border:none; border-radius:5px; cursor:pointer;
        }
        #modalCloseBtn:hover { background-color: #45a049; }
        @keyframes fadeInModal {
            from { opacity: 0; } to { opacity: 1; }
        }
        @keyframes slideInModal {
            from { transform: translateY(-50px); opacity: 0; } 
            to   { transform: translateY(0); opacity: 1; }
        }
    </style>
    <div id="customModal" class="custom-modal">
        <div class="custom-modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h4>Username Copied!</h4>
            <p>The generated username has been copied to your clipboard.</p>
            <button class="btn btn-primary" id="modalCloseBtn">Cool</button>
        </div>
    </div>

    {{-- SweetAlert2 & Custom JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.21/dist/sweetalert2.all.min.js"></script>
    <script>
        // Generate random password
        function generateRandomPassword(length = 8) {
            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@$";
            let pwd = "";
            for (let i = 0; i < length; i++) {
                pwd += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return pwd;
        }
        function setRandomPassword() {
            document.getElementById('generatedPassword').value = generateRandomPassword(8);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initial password
            setRandomPassword();
            document.getElementById('changePasswordBtn').addEventListener('click', setRandomPassword);

            // Username generation on Name input
            const nameInput = document.getElementById('nameInput');
            const usernameInput = document.getElementById('generatedUsername');
            const nextCustomerId = {{ $nextCustomerId }}; // Provided by Controller
            nameInput.addEventListener('input', function() {
                const name = nameInput.value.trim().toLowerCase().replace(/\s+/g, '');
                if (name) {
                    const formattedName = name.replace(/[^a-zA-Z0-9]/g, '');
                    usernameInput.value = `${formattedName}_${nextCustomerId}`;
                } else {
                    usernameInput.value = '';
                }
            });

            // Copy Username to Clipboard
            usernameInput.addEventListener('click', function() {
                usernameInput.select();
                document.execCommand('copy');
                // Show custom modal
                document.getElementById('customModal').style.display = 'block';
            });
            document.getElementById('closeModal').addEventListener('click', () => {
                document.getElementById('customModal').style.display = 'none';
            });
            document.getElementById('modalCloseBtn').addEventListener('click', () => {
                document.getElementById('customModal').style.display = 'none';
            });
        });

        // Tab navigation and validation
        function nextTab() {
            const tabButtons = Array.from(document.querySelectorAll('#myTab button.nav-link'));
            const activeTab = document.querySelector('#myTab button.nav-link.active');

            // Validate Personal Info before moving on
            if (activeTab.id === 'personal-tab') {
                const requiredFields = document.querySelectorAll('#personal input[required], #personal select[required]');
                for (let field of requiredFields) {
                    if (!field.value.trim()) {
                        Swal.fire({ icon: 'warning', title: 'Please fill in all required fields.' });
                        return;
                    }
                }
            }
            // Validate Test Info
            if (activeTab.id === 'test-tab') {
                if (!document.querySelector('.test-check:checked')) {
                    Swal.fire({ icon: 'warning', title: 'Select at least one test.' });
                    return;
                }
            }
            // Validate Referral
            if (activeTab.id === 'referral-tab') {
                const referralVal = document.getElementById('referralType').value;
                if (referralVal === 'staff' && !document.querySelector('input[name="staffPanelId"]:checked')) {
                    Swal.fire({ icon: 'warning', title: 'Select a Staff Panel option.' });
                    return;
                }
                if (referralVal === 'external' && !document.querySelector('input[name="externalPanelId"]:checked')) {
                    Swal.fire({ icon: 'warning', title: 'Select an External Panel option.' });
                    return;
                }
                if (referralVal === 'referrer' && !document.querySelector('input[name="id"]:checked')) {
                    Swal.fire({ icon: 'warning', title: 'Select a Referrer.' });
                    return;
                }
            }
            const index = tabButtons.indexOf(activeTab);
            if (index < tabButtons.length - 1) tabButtons[index + 1].click();
        }
        function previousTab() {
            const tabButtons = Array.from(document.querySelectorAll('#myTab button.nav-link'));
            const index = tabButtons.indexOf(document.querySelector('#myTab button.nav-link.active'));
            if (index > 0) tabButtons[index - 1].click();
        }

        // Prevent Enter from submitting early
        document.getElementById('mainForm').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName.toLowerCase() !== 'textarea') {
                e.preventDefault();
                const tabButtons = Array.from(document.querySelectorAll('#myTab button.nav-link'));
                const activeIndex = tabButtons.indexOf(document.querySelector('#myTab button.nav-link.active'));
                if (activeIndex < tabButtons.length - 1) {
                    nextTab();
                } else {
                    document.getElementById('mainForm').submit();
                }
            }
        });

        // Payment & Test Selection Logic
        document.addEventListener('DOMContentLoaded', function() {
            const testChecks = document.querySelectorAll('.test-check');
            const testsJsonInput = document.getElementById('testsJson');
            const catFilter = document.getElementById('catFilter');
            const testsTable = document.getElementById('testsTable');
            const testRows = testsTable.querySelectorAll('tbody tr');

            function gatherSelectedTests() {
                const selected = [];
                testChecks.forEach(chk => {
                    if (chk.checked) {
                        selected.push({
                            addTestId: parseInt(chk.getAttribute('data-addTestId')),
                            testName: chk.getAttribute('data-testName'),
                            testCatId: chk.getAttribute('data-testCatId'),
                            testCost: parseFloat(chk.getAttribute('data-testCost')) || 0
                        });
                    }
                });
                testsJsonInput.value = JSON.stringify(selected);
                return selected;
            }
            testChecks.forEach(chk => {
                chk.addEventListener('change', function() {
                    gatherSelectedTests();
                    recalcPayment();
                });
            });
            catFilter.addEventListener('change', function() {
                const selectedCat = catFilter.value;
                testRows.forEach(row => {
                    const catCell = row.querySelector('.testCatCell');
                    if (!catCell) return;
                    const rowCat = catCell.textContent.trim().toLowerCase();
                    row.style.display = (selectedCat === 'all' || rowCat === selectedCat) ? '' : 'none';
                });
            });

            // Referral panel toggle
            const referralType = document.getElementById('referralType');
            const staffSection = document.getElementById('staffPanelSection');
            const externalSection = document.getElementById('externalPanelSection');
            const referrerSection = document.getElementById('referrerSection');

            referralType.addEventListener('change', function() {
                staffSection.style.display = 'none';
                externalSection.style.display = 'none';
                referrerSection.style.display = 'none';
                if (this.value === 'staff') staffSection.style.display = '';
                else if (this.value === 'external') externalSection.style.display = '';
                else if (this.value === 'referrer') referrerSection.style.display = '';
                recalcPayment();
            });

            // Payment calculation
            const netCostInput = document.getElementById('netCost');
            const panelDiscountInput = document.getElementById('panelDiscount');
            const netPayableInput = document.getElementById('netPayable');
            const receivedInput = document.getElementById('receivedInput');
            const pendingInput = document.getElementById('pendingInput');

            const panelTypeLabel = document.getElementById('panelTypeLabel');
            const panelNameLabel = document.getElementById('panelNameLabel');
            const staffRemainingLabel = document.getElementById('staffRemainingLabel');
            const externalDiscountLabel = document.getElementById('externalDiscountLabel');
            const panelInfoSection = document.getElementById('panelInfoSection');

            function calculateTestsTotal() {
                let total = 0;
                try {
                    const arr = JSON.parse(testsJsonInput.value) || [];
                    arr.forEach(t => total += t.testCost);
                } catch {}
                return total;
            }

            function applyPanelDiscount(rawTotal) {
                let discount = 0, net = rawTotal;
                const refVal = referralType.value;
                if (refVal === 'staff') {
                    const chk = document.querySelector('input[name="staffPanelId"]:checked');
                    if (chk) {
                        const rem = parseFloat(chk.getAttribute('data-remaining')) || 0;
                        discount = Math.min(rawTotal, rem);
                        net = rawTotal - discount;
                        panelTypeLabel.textContent = "Staff Panel";
                        panelNameLabel.textContent = chk.getAttribute('data-staff-name') || 'Unknown';
                        staffRemainingLabel.textContent = rem.toFixed(2);
                        externalDiscountLabel.textContent = "";
                        externalDiscountLabel.parentElement.style.display = 'none';
                        staffRemainingLabel.parentElement.style.display = '';
                        panelInfoSection.style.display = '';
                    }
                } else if (refVal === 'external') {
                    const chk = document.querySelector('input[name="externalPanelId"]:checked');
                    if (chk) {
                        const rem = parseFloat(chk.getAttribute('data-remaining')) || 0;
                        discount = Math.min(rawTotal, rem);
                        net = rawTotal - discount;
                        panelTypeLabel.textContent = "External Panel";
                        panelNameLabel.textContent = chk.getAttribute('data-panel-name') || 'Unknown';
                        externalDiscountLabel.textContent = rem.toFixed(2);
                        staffRemainingLabel.textContent = "";
                        staffRemainingLabel.parentElement.style.display = 'none';
                        externalDiscountLabel.parentElement.style.display = '';
                        panelInfoSection.style.display = '';
                    }
                } else if (refVal === 'referrer') {
                    const chk = document.querySelector('input[name="id"]:checked');
                    if (chk) {
                        panelTypeLabel.textContent = "Referrer";
                        panelNameLabel.textContent = chk.getAttribute('data-referrer-name') || 'Unknown';
                        staffRemainingLabel.textContent = "";
                        externalDiscountLabel.textContent = "";
                        staffRemainingLabel.parentElement.style.display = 'none';
                        externalDiscountLabel.parentElement.style.display = 'none';
                        panelInfoSection.style.display = '';
                        discount = 0;
                        net = rawTotal;
                    }
                } else {
                    discount = 0;
                    net = rawTotal;
                    panelInfoSection.style.display = 'none';
                }
                return { discount, net };
            }

            function recalcPayment() {
                gatherSelectedTests();
                const rawTotal = calculateTestsTotal();
                netCostInput.value = rawTotal.toFixed(2);

                const { discount: panelDisc, net: panelNet } = applyPanelDiscount(rawTotal);
                panelDiscountInput.value = panelDisc.toFixed(2);

                let finalNet = panelNet;
                let loyaltyPercent = 0;

                if (panelDisc > 0) {
                    document.getElementById('loyaltyDiscountPercent').value = '0';
                    document.getElementById('loyaltyDiscountAmount').value = '0';
                } else {
                    const payPhone = document.getElementById('paymentPhone').value.trim();
                    const loyaltyDivs = document.querySelectorAll('div[data-phone][data-percentage]');
                    let loyaltyPct = 0;
                    loyaltyDivs.forEach(div => {
                        if (div.getAttribute('data-phone') === payPhone) {
                            loyaltyPct = parseFloat(div.getAttribute('data-percentage')) || 0;
                        }
                    });
                    if (loyaltyPct > 0) {
                        const loyaltyAmt = rawTotal * (loyaltyPct / 100);
                        finalNet = rawTotal - loyaltyAmt;
                        document.getElementById('loyaltyDiscountPercent').value = loyaltyPct.toFixed(2) + ' %';
                        document.getElementById('loyaltyDiscountAmount').value = loyaltyAmt.toFixed(2);
                        document.getElementById('panelDiscount').value = '0';
                        panelInfoSection.style.display = 'none';
                        loyaltyPercent = loyaltyPct;
                    } else {
                        document.getElementById('loyaltyDiscountPercent').value = '0';
                        document.getElementById('loyaltyDiscountAmount').value = '0';
                    }
                }

                netPayableInput.value = finalNet.toFixed(2);

                let rec = parseFloat(receivedInput.value) || 0;
                if (rec > finalNet) {
                    Swal.fire({ icon: 'warning', title: 'Received cannot exceed Net Payable.' });
                    receivedInput.value = finalNet.toFixed(2);
                    rec = finalNet;
                }
                pendingInput.value = (finalNet - rec).toFixed(2);

                toggleDiscountVisibility(panelDisc, loyaltyPercent);
            }

            function toggleDiscountVisibility(panelDisc, loyaltyPct) {
                document.getElementById('panelDiscountContainer').style.display = panelDisc > 0 ? 'block' : 'none';
                document.getElementById('loyaltyDiscountContainerPercent').style.display = loyaltyPct > 0 ? 'block' : 'none';
                document.getElementById('loyaltyDiscountContainerAmount').style.display = loyaltyPct > 0 ? 'block' : 'none';
            }

            // Update paymentPhone on typing in personal phone
            document.getElementById('phonePersonal').addEventListener('input', function() {
                document.getElementById('paymentPhone').value = this.value;
                recalcPayment();
            });

            // Bind recalc to relevant inputs
            receivedInput.addEventListener('input', recalcPayment);
            document.querySelectorAll('input[name="staffPanelId"]').forEach(r => r.addEventListener('change', recalcPayment));
            document.querySelectorAll('input[name="externalPanelId"]').forEach(r => r.addEventListener('change', recalcPayment));

            // Initialize
            document.getElementById('testsJson').value = '[]';
            recalcPayment();
        });
    </script>
@endsection
