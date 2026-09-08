<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#4d2635] leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f5f3ff] min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ========================================================= --}}
            {{-- WELCOME --}}
            {{-- ========================================================= --}}

            <div class="bg-[#f8e9ed] overflow-hidden shadow-sm sm:rounded-lg border border-[#ead5dc]">

                <div class="p-6 text-[#4d2635]">

                    <h3 class="text-lg font-semibold">
                        Welcome, {{ auth()->user()->name }}!
                    </h3>

                    <p class="mt-1 text-[#80616d]">
                        You are logged in as a {{ ucfirst(auth()->user()->role) }}.
                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- PATIENT INFORMATION --}}
            {{-- ========================================================= --}}

            @if(auth()->user()->patientProfile)

                @php
                    $patient = auth()->user()->patientProfile;
                @endphp

                <div class="mt-6 bg-[#f8e9ed] overflow-hidden shadow-sm sm:rounded-lg border border-[#ead5dc]">

                    <div class="p-6 text-[#4d2635]">

                        <div class="flex items-center justify-between mb-5">

                            <div>

                                <h3 class="text-lg font-semibold">
                                    👤 My Information
                                </h3>

                                <p class="text-sm text-[#80616d] mt-1">
                                    Your registered patient information
                                </p>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">


                            {{-- ================================================= --}}
                            {{-- NAME --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Full Name
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ auth()->user()->name }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- EMAIL --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Email
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635] break-words">
                                    {{ auth()->user()->email }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- BLOOD GROUP --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Blood Group
                                </p>

                                <p class="text-2xl font-bold mt-1 text-[#4d2635]">
                                    {{ $patient->blood_group ?? 'Not provided' }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- PHONE --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Phone
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ $patient->phone ?? 'Not provided' }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- DATE OF BIRTH --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Date of Birth
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">

                                    @if($patient->date_of_birth)
                                        {{ $patient->date_of_birth->format('d M Y') }}
                                    @else
                                        Not provided
                                    @endif

                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- GENDER --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Gender
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ $patient->gender ? ucfirst($patient->gender) : 'Not provided' }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- ZONE --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Zone
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ $patient->zone ?? 'Not provided' }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- ADDRESS --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5] md:col-span-2">

                                <p class="text-sm text-[#80616d]">
                                    Address
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ $patient->address_line ?? 'Not provided' }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- EMERGENCY CONTACT NAME --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Emergency Contact
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ $patient->emergency_contact_name ?? 'Not provided' }}
                                </p>

                            </div>


                            {{-- ================================================= --}}
                            {{-- EMERGENCY CONTACT PHONE --}}
                            {{-- ================================================= --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Emergency Contact Phone
                                </p>

                                <p class="text-base font-semibold mt-1 text-[#4d2635]">
                                    {{ $patient->emergency_contact_phone ?? 'Not provided' }}
                                </p>

                            </div>


                        </div>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- DONOR BADGE SECTION --}}
            {{-- ========================================================= --}}

            @if(auth()->user()->donorProfile)

                @php
                    $donor = auth()->user()->donorProfile;
                @endphp

                <div class="mt-6 bg-[#f8e9ed] overflow-hidden shadow-sm sm:rounded-lg border border-[#ead5dc]">

                    <div class="p-6 text-[#4d2635]">

                        <h3 class="text-lg font-semibold mb-4">
                            🩸 My Donor Status
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">


                            {{-- Donation Count --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Successful Donations
                                </p>

                                <p class="text-2xl font-bold mt-1 text-[#4d2635]">
                                    {{ $donor->donation_count }}
                                </p>

                            </div>


                            {{-- Badge --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Donor Badge
                                </p>

                                <p class="text-2xl font-bold mt-1 text-[#4d2635]">
                                    {{ $donor->badge_name }}
                                </p>

                                <p class="text-sm text-[#80616d] mt-1">
                                    {{ ucfirst($donor->donor_badge) }}
                                </p>

                            </div>


                            {{-- Discount --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Shop Discount
                                </p>

                                <p class="text-2xl font-bold mt-1 text-[#4d2635]">
                                    {{ $donor->shop_discount }}%
                                </p>

                            </div>


                            {{-- Priority --}}

                            <div class="border border-[#e4c9d1] rounded-lg p-4 bg-[#f3dfe5]">

                                <p class="text-sm text-[#80616d]">
                                    Donor Priority
                                </p>

                                <p class="text-2xl font-bold mt-1 text-[#4d2635]">
                                    Level {{ $donor->donor_priority }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- NOTIFICATIONS --}}
            {{-- ========================================================= --}}

            @if(auth()->user()->notifications()->count() > 0)

                <div class="mt-6 bg-[#f8e9ed] overflow-hidden shadow-sm sm:rounded-lg border border-[#ead5dc]">

                    <div class="p-6 text-[#4d2635]">

                        <div class="flex items-center justify-between mb-4">

                            <h3 class="text-lg font-semibold">
                                🔔 My Notifications
                            </h3>

                            <span class="text-sm text-[#80616d]">
                                {{ auth()->user()->unreadNotifications()->count() }} unread
                            </span>

                        </div>


                        @foreach(auth()->user()->notifications()->latest()->get() as $notification)

                            <div class="border border-[#e4c9d1] rounded-lg p-4 mb-4 bg-[#f3dfe5]">


                                {{-- Notification Title --}}

                                <p class="font-semibold text-[#4d2635]">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>


                                {{-- Message --}}

                                <p class="mt-2 text-[#5a3040]">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>


                                {{-- URL --}}

                                @if(isset($notification->data['url']))

                                    <a
                                        href="{{ $notification->data['url'] }}"
                                        class="inline-block mt-3 text-sm font-semibold text-[#b85c78] hover:text-[#7a4054]"
                                    >
                                        View Blood Samples →
                                    </a>

                                @endif


                                {{-- Sample Code --}}

                                @if(isset($notification->data['sample_code']))

                                    <p class="mt-2 text-sm text-[#5a3040]">
                                        <strong>Sample Code:</strong>
                                        {{ $notification->data['sample_code'] }}
                                    </p>

                                @endif


                                {{-- Donation Count --}}

                                @if(isset($notification->data['donation_count']))

                                    <p class="mt-2 text-sm text-[#5a3040]">
                                        <strong>Total Successful Donations:</strong>
                                        {{ $notification->data['donation_count'] }}
                                    </p>

                                @endif


                                {{-- Badge --}}

                                @if(isset($notification->data['badge_name']))

                                    <p class="mt-2 text-sm text-[#5a3040]">
                                        <strong>Donor Badge:</strong>
                                        {{ $notification->data['badge_name'] }}
                                    </p>

                                @endif


                                {{-- Discount --}}

                                @if(isset($notification->data['shop_discount']))

                                    <p class="mt-2 text-sm text-[#5a3040]">
                                        <strong>Shop Discount:</strong>
                                        {{ $notification->data['shop_discount'] }}%
                                    </p>

                                @endif


                                {{-- Rejection Reason --}}

                                @if(isset($notification->data['reason']))

                                    <p class="mt-2 text-sm text-[#5a3040]">
                                        <strong>Reason:</strong>
                                        {{ $notification->data['reason'] }}
                                    </p>

                                @endif


                                {{-- Read Status --}}

                                @if($notification->read_at)

                                    <p class="mt-2 text-sm text-green-600">
                                        ✓ Read
                                    </p>

                                @else

                                    <p class="mt-2 text-sm text-red-600">
                                        ● Unread
                                    </p>

                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @else

                <div class="mt-6 bg-[#f8e9ed] overflow-hidden shadow-sm sm:rounded-lg border border-[#ead5dc]">

                    <div class="p-6 text-[#4d2635]">

                        <h3 class="text-lg font-semibold">
                            🔔 My Notifications
                        </h3>

                        <p class="mt-2 text-[#80616d]">
                            You currently have no notifications.
                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>