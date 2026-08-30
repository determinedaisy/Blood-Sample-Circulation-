<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Sample Card</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    @php
        // Fetch the perfectly calculated status
        $status = $sample->overallStatus();
        
        // Dynamically assign Tailwind colors based on the admin's actions
        $statusColor = match($status) {
            'accepted', 'approved', 'delivered' => 'bg-green-100 text-green-800 border-green-200',
            'rejected', 'declined' => 'bg-red-100 text-red-800 border-red-200',
            'in_transit', 'collector_assigned' => 'bg-blue-100 text-blue-800 border-blue-200',
            default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        };
        
        $statusText = ucwords(str_replace('_', ' ', $status));
    @endphp

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200 relative">
        
        <!-- NEW: Dynamic Status Badge -->
        <div class="absolute top-4 right-4 z-10">
            <span class="px-3 py-1.5 rounded-full text-xs font-extrabold uppercase border shadow-sm {{ $statusColor }}">
                {{ $statusText }}
            </span>
        </div>

        <!-- Header / Logo Area -->
        <div class="bg-blue-600 p-6 text-center pt-10">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                <span class="text-blue-600 text-3xl font-extrabold">🩸</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-wide">Blood Circulation Inc.</h1>
            <p class="text-blue-100 text-sm mt-1">Official Sample Custody Record</p>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Sample Access Code</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $sample->sample_code }}</p>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Patient Name</p>
                    <p class="text-lg font-bold text-gray-800">{{ $sample->patient->name ?? 'Unknown' }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Blood Group</p>
                        <!-- FIX: Pulling directly from the sample's blood_type field -->
                        <p class="text-lg font-bold text-red-600">{{ $sample->blood_type ?? 'Pending Test' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 font-semibold uppercase">Sample Type</p>
                        <p class="text-lg font-bold text-gray-800">{{ $sample->sample_type ?? 'Standard' }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Registered On</p>
                    <!-- FIX: Fallback to created_at so the date never fails -->
                    <p class="text-md font-bold text-gray-800">
                        @if($sample->collected_at)
                            {{ $sample->collected_at->format('d M Y, h:i A') }}
                        @else
                            {{ $sample->created_at->format('d M Y, h:i A') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-400">Scan verified by Central Laboratory System.</p>
        </div>
    </div>

</body>
</html>