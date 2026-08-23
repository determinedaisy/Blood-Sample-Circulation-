<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">
                Contact Receptionist
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Send your sample requirements to reception for assistance.
            </p>
        </div>
    </x-slot>


    <style>
        .contact-page {
            background:
                radial-gradient(circle at top right, #eef2ff 0, transparent 28%),
                linear-gradient(180deg, #f8fafc 0%, #f4f6fb 100%);
            min-height: 100vh;
            padding: 38px 20px 60px;
        }

        .contact-container {
            max-width: 1180px;
            margin: 0 auto;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 26px;
            align-items: start;
        }

        .main-card,
        .side-card {
            background: rgba(255,255,255,.96);
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(15,23,42,.06);
        }

        .main-card {
            padding: 30px;
        }

        .side-card {
            padding: 24px;
        }

        .section-title {
            font-size: 21px;
            font-weight: 800;
            color: #111827;
        }

        .section-subtitle {
            margin-top: 5px;
            color: #6b7280;
            font-size: 14px;
        }

        .patient-box {
            margin-top: 24px;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            border: 1px solid #dbeafe;
            border-radius: 18px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
        }

        .form-row {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        .form-label {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            padding-top: 13px;
        }

        .form-control {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #d1d5db;
            padding: 12px 14px;
            background: white;
            color: #111827;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
            outline: none;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .footer-row {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .trust-note {
            font-size: 13px;
            color: #6b7280;
        }

        .button-row {
            display: flex;
            gap: 12px;
        }

        .btn-cancel,
        .btn-send {
            border-radius: 12px;
            padding: 11px 18px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel {
            border: 1px solid #d1d5db;
            color: #374151;
            background: white;
        }

        .btn-send {
            border: none;
            color: white;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            box-shadow: 0 8px 20px rgba(79,70,229,.22);
            cursor: pointer;
        }

        .how-title {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 20px;
        }

        .how-step {
            display: flex;
            gap: 12px;
            margin-bottom: 22px;
        }

        .how-number {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .how-step strong {
            display: block;
            color: #111827;
            font-size: 14px;
        }

        .how-step span {
            display: block;
            margin-top: 3px;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .important-card {
            margin-top: 18px;
            border-radius: 18px;
            border: 1px solid #fecdd3;
            background: #fff1f2;
            padding: 18px;
        }

        .important-card strong {
            color: #be123c;
        }

        .important-card p {
            margin-top: 6px;
            color: #881337;
            font-size: 13px;
            line-height: 1.5;
        }

        .error-box {
            margin-bottom: 22px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 14px;
            padding: 16px;
        }

        @media (max-width: 950px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .form-label {
                padding-top: 0;
            }

            .main-card {
                padding: 22px;
            }
        }
    </style>


    <div class="contact-page">

        <div class="contact-container">

            @if($errors->any())

                <div class="error-box">
                    <strong>Please fix the following:</strong>

                    <ul class="mt-2 list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            @endif


            <div class="contact-grid">

                <div class="main-card">

                    <div>
                        <div class="section-title">
                            Request Details
                        </div>

                        <div class="section-subtitle">
                            Tell reception what sample assistance you need.
                        </div>
                    </div>


                    <div class="patient-box">

                        <div class="patient-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                You are sending this request as
                            </div>

                            <div class="font-bold text-gray-900 mt-1">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('reception-requests.store') }}"
                    >

                        @csrf


                        <div class="form-row">

                            <label
                                for="sample_type"
                                class="form-label"
                            >
                                Sample Type
                            </label>

                            <select
                                id="sample_type"
                                name="sample_type"
                                required
                                class="form-control"
                            >
                                <option value="">
                                    Select sample type
                                </option>

                                <option
                                    value="Whole Blood"
                                    @selected(old('sample_type') === 'Whole Blood')
                                >
                                    Whole Blood
                                </option>

                                <option
                                    value="Plasma"
                                    @selected(old('sample_type') === 'Plasma')
                                >
                                    Plasma
                                </option>

                                <option
                                    value="Serum"
                                    @selected(old('sample_type') === 'Serum')
                                >
                                    Serum
                                </option>

                                <option
                                    value="Platelets"
                                    @selected(old('sample_type') === 'Platelets')
                                >
                                    Platelets
                                </option>
                            </select>

                        </div>


                        <div class="form-row">

                            <label
                                for="blood_type"
                                class="form-label"
                            >
                                Blood Type
                                <span class="text-gray-400 font-normal">
                                    (Optional)
                                </span>
                            </label>

                            <select
                                id="blood_type"
                                name="blood_type"
                                class="form-control"
                            >
                                <option value="">
                                    Select blood type
                                </option>

                                @foreach([
                                    'A+',
                                    'A-',
                                    'B+',
                                    'B-',
                                    'AB+',
                                    'AB-',
                                    'O+',
                                    'O-'
                                ] as $bloodType)

                                    <option
                                        value="{{ $bloodType }}"
                                        @selected(old('blood_type') === $bloodType)
                                    >
                                        {{ $bloodType }}
                                    </option>

                                @endforeach
                            </select>

                        </div>


                        <div class="form-row">

                            <label
                                for="notes"
                                class="form-label"
                            >
                                Message to Reception
                            </label>

                            <div>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    maxlength="1000"
                                    class="form-control"
                                    placeholder="Explain what assistance you need..."
                                >{{ old('notes') }}</textarea>

                                <div class="text-xs text-gray-400 mt-2 text-right">
                                    Maximum 1000 characters
                                </div>
                            </div>

                        </div>


                        <div class="footer-row">

                            <div class="trust-note">
                                🔒 Your request will be reviewed by the reception team.
                            </div>

                            <div class="button-row">

                                <a
                                    href="{{ route('reception-requests.patient.index') }}"
                                    class="btn-cancel"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="btn-send"
                                >
                                    ✉ Send to Reception
                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                <div>

                    <div class="side-card">

                        <div class="how-title">
                            How it works
                        </div>


                        <div class="how-step">

                            <div class="how-number">
                                1
                            </div>

                            <div>
                                <strong>
                                    You send your request
                                </strong>

                                <span>
                                    Fill in the sample details and send them to reception.
                                </span>
                            </div>

                        </div>


                        <div class="how-step">

                            <div class="how-number">
                                2
                            </div>

                            <div>
                                <strong>
                                    Reception reviews
                                </strong>

                                <span>
                                    A receptionist checks your information.
                                </span>
                            </div>

                        </div>


                        <div class="how-step">

                            <div class="how-number">
                                3
                            </div>

                            <div>
                                <strong>
                                    Request processed
                                </strong>

                                <span>
                                    Reception creates the actual sample request for you.
                                </span>
                            </div>

                        </div>


                        <div class="how-step">

                            <div class="how-number">
                                4
                            </div>

                            <div>
                                <strong>
                                    Admin handles it
                                </strong>

                                <span>
                                    The request continues to admin approval and collection.
                                </span>
                            </div>

                        </div>

                    </div>


                    <div class="important-card">

                        <strong>
                            Important
                        </strong>

                        <p>
                            Enter accurate information so reception can process
                            your request without delays.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>