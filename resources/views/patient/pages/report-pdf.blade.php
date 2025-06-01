<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Medical Report - #{{ $report->reportId }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .medical-report-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 18px rgba(0, 68, 255, 0.04);
            border: 1.5px solid #ececec;
            margin: 30px auto;
            padding: 30px 32px 22px 32px;
            max-width: 800px;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0d6efd;
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 18px 24px 14px 24px;
        }
        .hospital-logo {
            height: 48px;
            margin-right: 16px;
        }
        .report-section-title {
            color: #0060b6;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            letter-spacing: .3px;
            border-bottom: 1.2px solid #eee;
            padding-bottom: 3px;
        }
        ul.patient-info-list {
            list-style: none;
            padding-left: 0;
        }
        .patient-info-list li {
            display: inline-block;
            width: 48%;
            margin-bottom: 4px;
            font-size: 1.04rem;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 7px 4px;
            text-align: center;
        }
        th {
            background: #d9eafe;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 38px;
        }
        .signature-section .remarks {
            color: #555;
            font-size: 14px;
        }
        .signature-section .auth {
            text-align: right;
            margin-top: 16px;
        }
        .signature-section .auth .line {
            border-top: 1px solid #888;
            width: 160px;
            margin-left: auto;
        }
        .signature-section .auth .name {
            font-weight: bold;
            margin-top: 4px;
        }
        .signature-section .auth .title {
            font-size: 13px;
        }
        .signature-section .auth .note {
            margin-top: 3px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="medical-report-card">
        <!-- Report Header -->
        <div class="report-header">
            <div style="display:flex;align-items:center;">
                <img src="{{ public_path('assets/images/logo-dark.png') }}" alt="Lab Logo" class="hospital-logo">
                <div>
                    <h2 style="margin:0 0 2px 0;font-weight:700;letter-spacing:2px;font-size:20px;">Medi Core Laboratory</h2>
                    <div style="font-size:13px;">Trusted Diagnostic & Research Center</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-size: 13px;">Date: <b>{{ now()->format('d M, Y') }}</b></div>
                <div style="font-size: 13px;">Report #: <b>{{ $report->reportId ?? 'N/A' }}</b></div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="report-body">
            <div style="margin-bottom:14px;">
                <div class="report-section-title">Patient Information</div>
                <ul class="patient-info-list">
                    <li><b>Name:</b> {{ $customer->name ?? 'N/A' }}</li>
                    <li><b>Gender:</b> {{ ucfirst($customer->gender) ?? 'N/A' }}</li>
                    <li><b>Age:</b> {{ $customer->age ?? 'N/A' }}</li>
                    <li><b>Phone:</b> {{ $customer->phone ?? 'N/A' }}</li>
                    <li><b>Email:</b> {{ $customer->email ?? 'N/A' }}</li>
                </ul>
            </div>

            <div style="margin-bottom:14px;">
                <div class="report-section-title">Test Details</div>
                <ul class="patient-info-list">
                    <li><b>Test Name:</b> {{ $report->customerTest->test->testName ?? 'N/A' }}</li>
                    <li><b>Sample Type:</b> {{ $report->customerTest->test->typeSample ?? 'N/A' }}</li>
                    <li><b>Status:</b>
                        @if($report->signStatus == 'accepted')
                            <span style="color:green;font-weight:bold;">Accepted</span>
                        @else
                            <span style="color:#b78b00;font-weight:bold;">{{ ucfirst($report->signStatus) }}</span>
                        @endif
                    </li>
                </ul>
            </div>

            @if($report->reportChildren->isNotEmpty())
                <div style="margin-bottom:14px;">
                    <div class="report-section-title">Test Results</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Test Parameter</th>
                                <th>Gender</th>
                                <th>Min Range</th>
                                <th>Max Range</th>
                                <th>Result Value</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->reportChildren as $child)
                                <tr>
                                    <td>{{ $child->relatedTestRange->testTypeName ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($child->relatedTestRange->gender) ?? 'N/A' }}</td>
                                    <td>{{ $child->relatedTestRange->minRange ?? 'N/A' }}</td>
                                    <td>{{ $child->relatedTestRange->maxRange ?? 'N/A' }}</td>
                                    <td><b>{{ $child->reportValue ?? 'N/A' }}</b></td>
                                    <td>{{ $child->relatedTestRange->unit ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="color: #b78b00; margin-bottom:12px;">
                    No test results available for this report.
                </div>
            @endif

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="remarks">
                    <b>Remarks:</b> <span>{{ $report->comment ?? '---' }}</span>
                </div>
                <div class="auth">
                    <div class="line"></div>
                    <div class="name">Authorized By</div>
                    <div class="title">Consultant Pathologist</div>
                    <div class="note"><em>
                        No physical signature is required on this document. This report is digitally authenticated and valid without a handwritten signature.
                    </em></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
