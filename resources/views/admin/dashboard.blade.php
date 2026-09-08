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

        /* ==========================
           PAGE / GLOBAL BACKGROUND
        ========================== */

        body {
            background: #f5f8f9 !important;
            color: #4d2635;
        }

        header {
            background: #f8e9ed !important;
            border-color: #ead5dc !important;
        }


        /* ==========================
           ADMIN DASHBOARD
        ========================== */

        .admin-dashboard {
            max-width: 1500px;
            margin: 0 auto;
            padding: 40px 32px;
            background: #f5f8f9;
        }


        /* ==========================
           PAGE TITLE
        ========================== */

        .admin-page-title {
            margin: 0;
            color: #4d2635;
            font-size: 26px;
            font-weight: 700;
        }

        .admin-page-subtitle {
            margin-top: 6px;
            color: #80616d;
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
            background: #f8e9ed;
            border: 1px solid #ead5dc;
            border-radius: 12px;
            padding: 24px;
            transition:
                transform 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #d8b7c2;
            box-shadow: 0 4px 14px rgba(77, 38, 53, 0.08);
        }

        .stat-label {
            color: #80616d;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .stat-value {
            color: #4d2635;
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-value.accepted {
            color: #16a34a;
        }

        .stat-value.rejected {
            color: #dc2626;
        }

        .stat-value.rate {
            color: #b85c78;
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
            background: #f8e9ed;
            border: 1px solid #ead5dc;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(77, 38, 53, 0.04);
        }

        .chart-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .panel-title {
            color: #4d2635;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .chart-container {
            width: 100%;
            height: 310px;
            position: relative;
        }


        /* ==========================
           TIME RANGE TOGGLE
        ========================== */

        .chart-toggle {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px;
            background: #f3dfe5;
            border: 1px solid #ead5dc;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .chart-toggle button {
            border: none;
            background: transparent;
            color: #80616d;
            padding: 7px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition:
                background 0.2s ease,
                color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .chart-toggle button:hover {
            color: #4d2635;
        }

        .chart-toggle button.active {
            background: #ffffff;
            color: #b85c78;
            box-shadow: 0 1px 3px rgba(77, 38, 53, 0.12);
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
            color: #80616d;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #ead5dc;
        }

        .recent-table td {
            color: #4d2635;
            padding: 16px;
            border-bottom: 1px solid #ead5dc;
            font-size: 14px;
        }

        .recent-table tbody tr:hover {
            background: #f3dfe5;
        }

        .recent-table tbody tr:last-child td {
            border-bottom: none;
        }


        /* ==========================
           STATUS BADGES
        ========================== */

        .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-accepted {
            background: rgba(22, 163, 74, 0.10);
            color: #15803d;
        }

        .status-rejected {
            background: rgba(220, 38, 38, 0.10);
            color: #b91c1c;
        }

        .status-other {
            background: rgba(184, 92, 120, 0.10);
            color: #8f4b63;
        }

        .empty-message {
            color: #80616d !important;
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

            .chart-panel-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .chart-toggle {
                width: 100%;
            }

            .chart-toggle button {
                flex: 1;
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


            {{-- Accepted vs Rejected --}}

            <div class="dashboard-panel">

                <h3 class="panel-title">
                    Accepted vs Rejected
                </h3>

                <div class="chart-container">
                    <canvas id="acceptanceChart"></canvas>
                </div>

            </div>


            {{-- Daily Samples --}}

            <div class="dashboard-panel">

                <div class="chart-panel-header">

                    <h3 class="panel-title" id="dailyChartTitle">
                        Samples — Last 7 Days
                    </h3>


                    {{-- 7 / 30 Day Toggle --}}

                    <div class="chart-toggle">

                        <button
                            type="button"
                            id="sevenDaysButton"
                            class="active"
                        >
                            7 Days
                        </button>

                        <button
                            type="button"
                            id="thirtyDaysButton"
                        >
                            30 Days
                        </button>

                    </div>

                </div>


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


            /*
            |--------------------------------------------------------------------------
            | Make sure Chart.js is available
            |--------------------------------------------------------------------------
            */

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
                                    '#16a34a',
                                    '#dc2626'
                                ],

                                borderColor: '#f8e9ed',

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

                                        color: '#80616d',
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
            | Daily Samples
            |--------------------------------------------------------------------------
            |
            | The controller provides BOTH datasets:
            |
            | $dailyLabels7 / $dailyValues7
            | $dailyLabels30 / $dailyValues30
            |
            | The toggle below switches between them.
            |--------------------------------------------------------------------------
            */

            const dailyCanvas =
                document.getElementById('dailySamplesChart');


            const dailyChartTitle =
                document.getElementById('dailyChartTitle');


            const sevenDaysButton =
                document.getElementById('sevenDaysButton');


            const thirtyDaysButton =
                document.getElementById('thirtyDaysButton');


            if (dailyCanvas) {


                /*
                |--------------------------------------------------------------
                | Data from Laravel
                |--------------------------------------------------------------
                */

                const chartData = {

                    seven: {

                        labels:
                            @json($dailyLabels7),

                        values:
                            @json($dailyValues7)

                    },

                    thirty: {

                        labels:
                            @json($dailyLabels30),

                        values:
                            @json($dailyValues30)

                    }

                };


                /*
                |--------------------------------------------------------------
                | Create initial chart
                |--------------------------------------------------------------
                */

                const dailyChart =
                    new window.Chart(
                        dailyCanvas,
                        {

                            type: 'bar',

                            data: {

                                labels:
                                    chartData.seven.labels,

                                datasets: [{

                                    label: 'Samples',

                                    data:
                                        chartData.seven.values,

                                    backgroundColor:
                                        '#b85c78',

                                    borderRadius: 5

                                }]

                            },


                            options: {

                                responsive: true,
                                maintainAspectRatio: false,

                                animation: {

                                    duration: 300

                                },


                                plugins: {

                                    legend: {

                                        position: 'top',
                                        align: 'end',

                                        labels: {

                                            color: '#80616d',

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
                                                '#80616d'

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
                                                '#80616d',

                                            precision:
                                                0

                                        },

                                        grid: {

                                            color:
                                                '#ead5dc'

                                        }

                                    }

                                }

                            }

                        }

                    );


                /*
                |--------------------------------------------------------------
                | Function to switch chart range
                |--------------------------------------------------------------
                */

                function updateDailyChart(range) {

                    const selectedData =
                        chartData[range];


                    dailyChart.data.labels =
                        selectedData.labels;


                    dailyChart.data.datasets[0].data =
                        selectedData.values;


                    dailyChart.update();


                    /*
                    | Update title
                    */

                    if (range === 'seven') {

                        dailyChartTitle.textContent =
                            'Samples — Last 7 Days';

                    } else {

                        dailyChartTitle.textContent =
                            'Samples — Last 30 Days';

                    }


                    /*
                    | Update active button
                    */

                    if (range === 'seven') {

                        sevenDaysButton.classList.add(
                            'active'
                        );

                        thirtyDaysButton.classList.remove(
                            'active'
                        );

                    } else {

                        thirtyDaysButton.classList.add(
                            'active'
                        );

                        sevenDaysButton.classList.remove(
                            'active'
                        );

                    }

                }


                /*
                |--------------------------------------------------------------
                | 7 Days button
                |--------------------------------------------------------------
                */

                sevenDaysButton.addEventListener(
                    'click',
                    function () {

                        updateDailyChart('seven');

                    }
                );


                /*
                |--------------------------------------------------------------
                | 30 Days button
                |--------------------------------------------------------------
                */

                thirtyDaysButton.addEventListener(
                    'click',
                    function () {

                        updateDailyChart('thirty');

                    }
                );

            }


        });

    </script>


</x-app-layout>