<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Day Trip Itinerary - Elahera Serisara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #ffffff; color: #1e293b; padding: 2rem; }
        .print-header { border-bottom: 2px solid #059669; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
            <div>
                <strong>Print / PDF Export Mode</strong> - Use your browser's print function (Ctrl + P) and choose "Save as PDF".
            </div>
            <button onclick="window.print()" class="btn btn-success fw-bold">Print Now</button>
        </div>

        <div class="print-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-success mb-1">Elahera Serisara</h2>
                <div class="text-muted small">Discover • Explore • Experience | Day Trip Plan</div>
            </div>
            <div class="text-end">
                <div class="fw-bold">Date: {{ $tripDate }}</div>
                <div class="small text-muted">Start: {{ $startLocation }} at {{ $startTime }} ({{ $transportMode }})</div>
            </div>
        </div>

        <h4 class="fw-bold mb-3">Planned Stops & Route Timeline</h4>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Destination</th>
                        <th>Hours & Closure</th>
                        <th>Entrance Fee</th>
                        <th>Stay</th>
                        <th>Drive</th>
                        <th>Key Guidance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalEstMin = 0; $totalEstMax = 0; @endphp
                    @foreach($stops as $idx => $stop)
                        @php
                            $d = $destinations->get($stop['id']);
                            if ($d) {
                                $totalEstMin += ($d->ticket_price_min ?: 0);
                                $totalEstMax += ($d->ticket_price_max ?: 0);
                            }
                        @endphp
                        <tr>
                            <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                            <td>
                                <strong class="fs-6">{{ $stop['title'] ?? ($d ? $d->getLocalizedTitle() : 'Destination') }}</strong>
                                @if($d && $d->nearest_bus_stop)
                                    <div class="small text-muted">Halt: {{ $d->nearest_bus_stop }}</div>
                                @endif
                            </td>
                            <td class="small">
                                @if($d)
                                    <div><strong>{{ $d->getFormattedOpeningHours() }}</strong></div>
                                    @if(!empty($d->closed_days))
                                        <div class="text-danger" style="font-size: 0.75rem;">Closed: {{ implode(', ', $d->closed_days) }}</div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="small fw-semibold text-success">
                                {{ $d ? $d->getFormattedTicketPrice() : 'Free' }}
                            </td>
                            <td>{{ $stop['stay'] ?? ($d ? $d->stay_duration_minutes : 60) }} mins</td>
                            <td>{{ $stop['travel'] ?? ($d ? $d->travel_time_minutes : 20) }} mins</td>
                            <td class="small text-danger">
                                {{ $d ? ($d->safety_alerts_en ?: 'Exercise normal outdoor caution') : '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total Estimated Ticket Costs:</th>
                        <th class="text-success">
                            @if($totalEstMin == 0 && $totalEstMax == 0)
                                Free Entry
                            @elseif($totalEstMin == $totalEstMax)
                                LKR {{ number_format($totalEstMin) }}
                            @else
                                LKR {{ number_format($totalEstMin) }} - {{ number_format($totalEstMax) }}
                            @endif
                        </th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-3 bg-light rounded border text-muted small">
            <strong>Travel Notice:</strong> Elahera Serisara provides this itinerary for exploratory convenience. Travel times may vary based on weather conditions and traffic. Please respect local wildlife guidelines and irrigation canal safety signs.
        </div>
    </div>
</body>
</html>
