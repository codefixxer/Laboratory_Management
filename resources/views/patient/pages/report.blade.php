    @extends('patient.layouts.app')

    @section('content')
 

    @if($report->reportChildren->isNotEmpty())
        <div class="mt-4">
            <h4 class="text-secondary">Reference Ranges</h4>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Test Name</th>
                        <th>Gender</th>
                        <th>Minimum Range</th>
                        <th>Maximum Range</th>
                        <th>Report Value</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report->reportChildren as $child)
                        <tr>
                            <td>{{ $child->relatedTestRange->testTypeName ?? 'N/A' }}</td>
<td>{{ $child->relatedTestRange->gender ?? 'N/A' }}</td>
<td>{{ $child->relatedTestRange->minRange ?? 'N/A' }}</td>
<td>{{ $child->relatedTestRange->maxRange ?? 'N/A' }}</td>
<td>{{ $child->reportValue ?? 'N/A' }}</td>
<td>{{ $child->relatedTestRange->unit ?? 'N/A' }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">
            No test range available for this report.
        </div>
    @endif

    @endsection
