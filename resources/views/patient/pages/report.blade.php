@extends('patient.layouts.app')

@section('content')
    <style>
        .medical-report-card {
            background: #f8fafc;
            border-radius: 18px;
            box-shadow: 0 2px 18px rgba(0, 68, 255, 0.04), 0 0px 0px 0px;
            border: 1.5px solid #ececec;
            margin: 24px 0;
            padding: 0;
            overflow: hidden;
        }

        .report-header {
            background: linear-gradient(270deg, #0d6efd 40%, #9ecbff 100%);
            color: #fff;
            padding: 28px 30px 16px 30px;
        }

        .report-header .hospital-logo {
            height: 54px;
            margin-right: 14px;
        }

        .report-body {
            padding: 26px 32px 18px 32px;
        }

        .report-section-title {
            color: #0060b6;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 10px;
            letter-spacing: .3px;
        }

        .patient-info-list .list-group-item {
            border: 0;
            padding: 4px 0 4px 0;
            font-size: 1.05rem;
        }

        .signature-section {
            margin-top: 38px;
        }

        @media print {
            .medical-report-card {
                box-shadow: none !important;
                border: 1px solid #bbb;
            }

            .report-header,
            .signature-section {
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .btn,
            nav,
            .sidebar,
            .header,
            .footer {
                display: none !important;
            }
        }
    </style>

    <div class="container">
        <div id="reportToDownload" class="medical-report-card mx-auto" style="max-width: 880px;">

            <!-- Report Header -->
            <div class="report-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="Lab Logo" class="hospital-logo">
                    <div>
                        <h2 class="mb-0" style="font-weight: 700; letter-spacing: 2px;">Medi Core Laboratory</h2>
                        <div style="font-size:15px; font-weight: 400;">Trusted Diagnostic & Research Center</div>
                    </div>
                </div>
                <div class="text-end">
                    <div style="font-size: 15px;">Date: <b>{{ now()->format('d M, Y') }}</b></div>
                    <div style="font-size: 15px;">Report #: <b>{{ $report->reportId ?? 'N/A' }}</b></div>
                </div>
            </div>

            <!-- Patient Info -->
            <div class="report-body">
                <div class="mb-4">
                    <div class="report-section-title mb-2">Patient Information</div>
                    <ul class="list-group list-group-horizontal-md flex-wrap patient-info-list">
                        <li class="list-group-item flex-fill"><b>Name:</b> {{ $customer->name ?? 'N/A' }}</li>
                        <li class="list-group-item flex-fill"><b>Gender:</b> {{ ucfirst($customer->gender) ?? 'N/A' }}</li>
                        <li class="list-group-item flex-fill"><b>Age:</b> {{ $customer->age ?? 'N/A' }}</li>
                        <li class="list-group-item flex-fill"><b>Phone:</b> {{ $customer->phone ?? 'N/A' }}</li>
                        <li class="list-group-item flex-fill"><b>Email:</b> {{ $customer->email ?? 'N/A' }}</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <div class="report-section-title mb-2">Test Details</div>
                    <ul class="list-group list-group-horizontal-md flex-wrap patient-info-list">
                        <li class="list-group-item flex-fill"><b>Test Name:</b>
                            {{ $report->customerTest->test->testName ?? 'N/A' }}</li>
                        <li class="list-group-item flex-fill"><b>Sample Type:</b>
                            {{ $report->customerTest->test->typeSample ?? 'N/A' }}</li>
                        <li class="list-group-item flex-fill"><b>Status:</b>
                            @if ($report->signStatus == 'accepted')
                                <span class="badge bg-success">Accepted</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($report->signStatus) }}</span>
                            @endif
                        </li>
                    </ul>
                </div>

                @if ($report->reportChildren->isNotEmpty())
                    <div class="mb-4">
                        <div class="report-section-title mb-2">Test Results</div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>Test Parameter</th>
                                        <th>Gender</th>
                                        <th>Min Range</th>
                                        <th>Max Range</th>
                                        <th>Result Value</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report->reportChildren as $child)
                                        <tr class="text-center">
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
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        No test results available for this report.
                    </div>
                @endif

                <div class="row signature-section">
                    <div class="col-md-8">
                        <div class="mt-3" style="color:#888;">
                            <b>Remarks:</b><br>
                            <span>{{ $report->comment ?? '---' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="mt-3" style="border-top:1px solid #888; width:80%; margin-left:auto;">
                            <div style="font-weight:600; font-size:17px; margin-top:7px;">Authorized By</div>
                            <span style="font-size:13px;">Consultant Pathologist</span>
                            <div class="mt-2" style="font-size:13px; color:#555;">
                                <em>
                                    This report does not require a physical signature, as it is digitally authenticated.
                                </em>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
        
    <div class="text-center mb-4">
    <button id="downloadReportBtn" class="btn btn-primary shadow-sm">
        <i class="fa fa-download me-2"></i> Download Report (PDF)
    </button>
</div>
    </div>
    

    {{-- @if (isset($report) && $report->reportId)
        <div class="text-end mb-4">
            <a href="{{ route('patient.report.download', $report->reportId) }}" class="btn btn-primary shadow mb-4">
                <i class="fa fa-download"></i> Download Report (PDF)
            </a>
        </div>
    @endif --}}



    <!-- jsPDF & html2canvas CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.getElementById('downloadReportBtn').addEventListener('click', function() {
            const element = document.getElementById('reportToDownload');
            // Increase scale for high-res
            html2canvas(element, {
                scale: 4,
                useCORS: true
            }).then(function(canvas) {
                const imgData = canvas.toDataURL('image/png', 1.0);
                // Get width/height ratio
                const pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                // Calculate new dimensions
                const imgWidth = pageWidth - 20; // 10mm margin each side
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let position = 10;

                // Multi-page support
                if (imgHeight < pageHeight - 20) {
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight, '', 'FAST');
                } else {
                    let remainingHeight = imgHeight;
                    let y = 10;
                    pdf.addImage(imgData, 'PNG', 10, y, imgWidth, imgHeight, '', 'FAST');
                    while (remainingHeight > pageHeight - 20) {
                        pdf.addPage();
                        y = 10 - (imgHeight - remainingHeight);
                        pdf.addImage(imgData, 'PNG', 10, y, imgWidth, imgHeight, '', 'FAST');
                        remainingHeight -= (pageHeight - 20);
                    }
                }
                pdf.save('medical-report-{{ $report->reportId ?? 'patient' }}.pdf');
            });
        });
    </script>

@endsection
