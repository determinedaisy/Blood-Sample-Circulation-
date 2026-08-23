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


                <p class="text-lg mb-8">
                    Press the button to find nearby compatible blood donors.
                </p>


                <form id="sosForm" method="POST" action="{{ route('sos.store') }}">

                    @csrf


                    <input type="hidden"
                           name="latitude"
                           id="latitude">


                    <input type="hidden"
                           name="longitude"
                           id="longitude">



                    <button
                        type="button"
                        onclick="getLocation()"
                        class="bg-red-600 hover:bg-red-700
                               text-white font-bold
                               py-4 px-10
                               rounded-full
                               text-xl
                               shadow-lg">

                        🚨 SEND SOS

                    </button>


                </form>


            </div>

        </div>

    </div>

</div>



<script>

function getLocation(){

    console.log("SOS clicked");


    if (!navigator.geolocation){

        alert("GPS is not supported");
        return;

    }


    navigator.geolocation.getCurrentPosition(

        function(position){


            console.log("Location received");


            document.getElementById('latitude').value =
                position.coords.latitude;


            document.getElementById('longitude').value =
                position.coords.longitude;



            document.getElementById('sosForm').submit();


        },


        function(error){

            console.log(error);

            alert("Please allow location access");

        }

    );

}

</script>


</x-app-layout>