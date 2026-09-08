<x-app-layout>

<x-slot name="header">

    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Emergency Blood SOS
    </h2>

</x-slot>


<div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="p-6 text-gray-100 text-center">

                <h1 class="text-4xl font-bold mb-6">
                    🚨 Emergency Blood SOS
                </h1>


                <p class="text-lg mb-3">
                    Request the blood group you need and find compatible
                    donors within 2 km.
                </p>


                <p class="text-sm text-gray-400 mb-8">
                    Only verified, willing, available, and medically
                    compatible donors within 2 km will be shown.
                </p>


                <form
                    id="sosForm"
                    method="POST"
                    action="{{ route('sos.store') }}"
                >

                    @csrf


                    <!--
                    |--------------------------------------------------------------------------
                    | REQUESTED BLOOD GROUP
                    |--------------------------------------------------------------------------
                    -->

                    <div class="max-w-md mx-auto mb-8 text-left">

                        <label
                            for="blood_group"
                            class="block text-lg font-semibold mb-3 text-gray-100"
                        >
                            Select Required Blood Group
                        </label>


                        <select
                            name="blood_group"
                            id="blood_group"
                            required
                            class="w-full
                                   rounded-xl
                                   border-gray-600
                                   bg-gray-700
                                   text-white
                                   px-4
                                   py-3
                                   text-lg
                                   focus:border-red-500
                                   focus:ring-red-500"
                        >

                            <option value="">
                                -- Select Blood Group --
                            </option>

                            <option value="A+">
                                A+
                            </option>

                            <option value="A-">
                                A-
                            </option>

                            <option value="B+">
                                B+
                            </option>

                            <option value="B-">
                                B-
                            </option>

                            <option value="AB+">
                                AB+
                            </option>

                            <option value="AB-">
                                AB-
                            </option>

                            <option value="O+">
                                O+
                            </option>

                            <option value="O-">
                                O-
                            </option>

                        </select>


                        @error('blood_group')

                            <p class="mt-2 text-sm text-red-400">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | TEST LOCATION
                    |--------------------------------------------------------------------------
                    |
                    | This is the same location used in DonorSeeder.php.
                    |
                    | Latitude:  23.8103
                    | Longitude: 90.4125
                    |
                    | The backend STILL enforces the maximum 2 km distance.
                    |
                    -->

                    <input
                        type="hidden"
                        name="latitude"
                        id="latitude"
                        value="23.8103"
                    >


                    <input
                        type="hidden"
                        name="longitude"
                        id="longitude"
                        value="90.4125"
                    >


                    <button
                        type="submit"
                        id="sosButton"
                        class="bg-red-600 hover:bg-red-700
                               text-white font-bold
                               py-4 px-10
                               rounded-full
                               text-xl
                               shadow-lg
                               transition
                               duration-200"
                    >

                        🚨 SEND SOS REQUEST

                    </button>


                    <p
                        id="locationStatus"
                        class="mt-6 text-sm text-gray-400"
                    >
                        Select the blood group you need and send the SOS request.
                    </p>


                </form>

            </div>

        </div>

    </div>

</div>


<script>

    document
        .getElementById('sosForm')
        .addEventListener('submit', function (event) {

            const bloodGroup =
                document.getElementById('blood_group').value;

            const button =
                document.getElementById('sosButton');

            const status =
                document.getElementById('locationStatus');


            if (!bloodGroup) {

                event.preventDefault();

                alert(
                    'Please select the blood group you need.'
                );

                return;

            }


            button.disabled = true;

            button.innerText =
                "🚨 SEARCHING FOR DONORS...";


            status.innerText =
                "Searching for compatible " +
                bloodGroup +
                " blood donors within 2 km...";

        });

</script>


</x-app-layout>
