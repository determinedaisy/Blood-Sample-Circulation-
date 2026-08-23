<x-app-layout>


<x-slot name="header">

    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
        Emergency Blood SOS
    </h2>

</x-slot>



<div class="py-12">


<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



<!-- Emergency Status Card -->

<div class="bg-red-900/30 border border-red-500 rounded-xl p-6 mb-8">


    <h1 class="text-3xl font-bold text-red-400">
        🚨 Emergency SOS Activated
    </h1>


    <p class="text-gray-200 mt-3">
        Searching for compatible blood donors near you...
    </p>



    <div class="mt-4">

        <span class="bg-red-600 text-white px-4 py-2 rounded-full">

            Searching

        </span>

    </div>


</div>





<!-- Donor Section -->


<h2 class="text-2xl font-bold text-white mb-5">

    🩸 Nearby Compatible Donors

</h2>





<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">



@forelse($donors as $donor)



<div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">



    <div class="flex items-center justify-between">


        <h3 class="text-xl font-bold text-white">

            {{ $donor->user->name ?? 'Unknown Donor' }}

        </h3>



        <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full">

            Available

        </span>


    </div>





    <div class="mt-5 text-gray-300 space-y-3">


        <p>

            🩸 Blood Group:

            <span class="font-bold text-white">

                {{ $donor->blood_group }}

            </span>

        </p>




        <p>

            📞 Phone:

            <span class="font-bold text-white">

                {{ $donor->phone }}

            </span>

        </p>





        <p>

            📍 Distance:

            <span class="font-bold text-white">

                {{ $donor->distance ?? 'N/A' }} km away

            </span>

        </p>



    </div>





 <div class="mt-6 flex gap-3">


    <a href="tel:{{ $donor->phone }}"
       class="flex-1 h-12 flex items-center justify-center
       bg-green-600 hover:bg-green-700
       text-white font-bold rounded-lg">


        ☎ Call


    </a>




    @if(in_array($donor->id, $requestedDonors ?? []))


        <button
        disabled
        class="flex-1 h-12
        bg-gray-500
        text-white font-bold rounded-lg">


            ✅ Requested


        </button>



    @else


        <form method="POST"
        action="{{ route('donor.request',$donor->id) }}"
        class="flex-1">


            @csrf


            <button
            type="submit"
            class="w-full h-12
            bg-blue-600 hover:bg-blue-700
            text-white font-bold rounded-lg">


                📩 Request


            </button>


        </form>


    @endif



</div>




</div>




@empty



<div class="bg-gray-800 p-6 rounded-xl text-white">

    No compatible donors found.

</div>



@endforelse



</div>



</div>


</div>


</x-app-layout>