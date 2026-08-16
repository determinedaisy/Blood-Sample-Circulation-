<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="admin-page-title">
                Admin Dashboard
            </h2>

            <p class="admin-page-subtitle">
                Blood Sample Circulation Overview
            </p>
        </div>
    </x-slot>


    <style>

        .admin-dashboard {
            max-width: 1500px;
            margin: 0 auto;
            padding: 40px 32px;
        }


        .admin-page-title {
            margin: 0;
            color: #f8fafc;
            font-size: 26px;
            font-weight: 700;
        }


        .admin-page-subtitle {
            margin-top: 6px;
            color: #94a3b8;
            font-size: 15px;
        }


        /* ==========================
           STATISTIC CARDS
        ========================== */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }


        .stat-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 24px;
            transition: transform 0.2s ease,
                        border-color 0.2s ease;
        }


        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #475569;
        }


        .stat-label {
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }


        .stat-value {
            color: #f8fafc;
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }


        .stat-value.accepted {
            color: #22c55e;
        }


        .stat-value.rejected {
            color: #ef4444;
        }


        .stat-value.rate {
            color: #60a5fa;
        }


        /* ==========================
           CHARTS
        ========================== */

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 20px;
            margin-bottom: 28px;
        }


        .dashboard-panel {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 24px;
        }


        .panel-title {
            color: #f8fafc;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 20px 0;
        }


        .chart-container {
            width: 100%;
            height: 310px;
            position: relative;
        }


        /* ==========================
           TABLE
        ========================== */

        .table-wrapper {
            overflow-x: auto;
        }


        .recent-table {
            width: 100%;
            border-collapse: collapse;
        }


        .recent-table th {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #334155;
        }


        .recent-table td {
            color: #e2e8f0;
            padding: 16px;
            border-bottom: 1px solid #334155;
            font-size: 14px;
        }


        .recent-table tbody tr:hover {
            background: #263449;
        }


        .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }


        .status-accepted {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }


        .status-rejected {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }


        .status-other {
            background: rgba(148, 163, 184, 0.15);
            color: #cbd5e1;
        }


        .empty-message {
            color: #94a3b8 !important;
            text-align: center;
            padding: 30px !important;
        }


        /* ==========================
           RESPONSIVE
        ========================== */

        @media (max-width: 1000px) {

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            .admin-dashboard {
                padding: 25px 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>



    <div class="admin-dashboard">


        {{-- ==========================
             STATISTICS
        ========================== --}}

        <div class="stats-grid">


            <div class="stat-card">

                <div class="stat-label">
                    Total Samples
                </div>

                <div class="stat-value">
                    {{ $totalSamples }}
                </div>

            </div>



            <div class="stat-card">

                <div class="stat-label">
                    Accepted
                </div>

                <div class="stat-value accepted">
                    {{ $acceptedSamples }}
                </div>

            </div>



            <div class="stat-card">

                <div class="stat-label">
                    Rejected
                </div>

                <div class="stat-value rejected">
                    {{ $rejectedSamples }}
                </div>

            </div>



            <div class="stat-card">

                <div class="stat-label">
                    Acceptance Rate
                </div>

                <div class="stat-value rate">
                    {{ $acceptanceRate }}%
                </div>

            </div>


        </div>



        {{-- ==========================
             CHARTS
        ========================== --}}

        <div class="charts-grid">


            <div class="dashboard-panel">

                <h3 class="panel-title">
                    Accepted vs Rejected
                </h3>

                <div class="chart-container">
                    <canvas id="acceptanceChart"></canvas>
                </div>

            </div>



            <div class="dashboard-panel">

                <h3 class="panel-title">
                    Samples — Last 7 Days
                </h3>

                <div class="chart-container">
                    <canvas id="dailySamplesChart"></canvas>
                </div>

            </div>


        </div>



        {{-- ==========================
             RECENT SAMPLES
        ========================== --}}

        <div class="dashboard-panel">


            <h3 class="panel-title">
                Recent Samples
            </h3>


            <div class="table-wrapper">


                <table class="recent-table">


                    <thead>

                        <tr>

                            <th>
                                Sample ID
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        @forelse ($recentSamples as $sample)


                            <tr>


                                <td>

                                    {{ $sample->sample_code
                                        ?? $sample->sample_id
                                        ?? ('BS-' . str_pad($sample->id, 4, '0', STR_PAD_LEFT))
                                    }}

                                </td>



                                <td>


                                    @if ($sample->status === 'accepted')

                                        <span class="status-badge status-accepted">
                                            Accepted
                                        </span>


                                    @elseif ($sample->status === 'rejected')

                                        <span class="status-badge status-rejected">
                                            Rejected
                                        </span>


                                    @else

                                        <span class="status-badge status-other">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $sample->status
                                                )
                                            ) }}

                                        </span>

                                    @endif


                                </td>



                                <td>

                                    {{ $sample->created_at
                                        ? $sample->created_at->format('d M Y, h:i A')
                                        : '-'
                                    }}

                                </td>


                            </tr>


                        @empty


                            <tr>

                                <td
                                    colspan="3"
                                    class="empty-message"
                                >
                                    No blood samples available.
                                </td>

                            </tr>


                        @endforelse


                    </tbody>


                </table>


            </div>


        </div>


    </div>



    {{-- ==========================
         CHARTS
    ========================== --}}

    <script>

        window.addEventListener('load', function () {


            if (!window.Chart) {

                console.error(
                    'Chart.js is not loaded.'
                );

                return;

            }



            /*
            |--------------------------------------------------------------------------
            | Accepted vs Rejected
            |--------------------------------------------------------------------------
            */

            const acceptanceCanvas =
                document.getElementById('acceptanceChart');


            if (acceptanceCanvas) {


                new window.Chart(
                    acceptanceCanvas,
                    {

                        type: 'doughnut',


                        data: {

                            labels: [
                                'Accepted',
                                'Rejected'
                            ],


                            datasets: [{

                                data: [
                                    {{ $acceptedSamples }},
                                    {{ $rejectedSamples }}
                                ],

                                backgroundColor: [
                                    '#22c55e',
                                    '#ef4444'
                                ],

                                borderColor: '#1e293b',

                                borderWidth: 4

                            }]

                        },


                        options: {

                            responsive: true,

                            maintainAspectRatio: false,


                            plugins: {

                                legend: {

                                    position: 'bottom',

                                    labels: {

                                        color: '#cbd5e1',

                                        padding: 20

                                    }

                                }

                            }

                        }

                    }
                );


            }



            /*
            |--------------------------------------------------------------------------
            | Samples - Last 7 Days
            |--------------------------------------------------------------------------
            */

            const dailyCanvas =
                document.getElementById('dailySamplesChart');


            if (dailyCanvas) {


                new window.Chart(
                    dailyCanvas,
                    {

                        type: 'bar',


                        data: {

                            labels:
                                @json($dailyLabels),


                            datasets: [{

                                label: 'Samples',

                                data:
                                    @json($dailyValues),

                                backgroundColor:
                                    '#6366f1',

                                borderRadius: 5

                            }]

                        },


                        options: {

                            responsive: true,

                            maintainAspectRatio: false,


                            plugins: {

    legend: {

        position: 'top',

        align: 'end',

        labels: {

            color: '#cbd5e1',

            boxWidth: 30,

            boxHeight: 10,

            padding: 10,

            font: {
                size: 12
            }

        }

    }

},


                            scales: {


                                x: {

                                    ticks: {

                                        color:
                                            '#94a3b8'

                                    },

                                    grid: {

                                        display:
                                            false

                                    }

                                },


                                y: {

                                    beginAtZero:
                                        true,

                                    ticks: {

                                        color:
                                            '#94a3b8',

                                        precision:
                                            0

                                    },

                                    grid: {

                                        color:
                                            '#334155'

                                    }

                                }


                            }

                        }

                    }
                );


            }


        });

    </script>


</x-app-layout>