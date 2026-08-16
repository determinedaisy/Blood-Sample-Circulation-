<?php

namespace App\Http\Controllers;


use App\Models\DonorRequest;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;


class DonorRequestController extends Controller
{

    public function store($donorId)
    {

        $patient = Patient::where(
            'user_id',
            Auth::id()
        )->firstOrFail();



        DonorRequest::create([

            'patient_id' => $patient->id,

            'donor_id' => $donorId,

            'status' => 'pending',

        ]);



        return redirect()
            ->route('sos.results')
            ->with(
                'success',
                'Request sent to donor successfully.'
            );

    }

}