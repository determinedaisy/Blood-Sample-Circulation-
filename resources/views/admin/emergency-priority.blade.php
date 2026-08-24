<x-app-layout>



    <style>

        /* =========================================
           PAGE
        ========================================= */

        .emergency-page {
            max-width: 1450px;
            margin: 0 auto;
            padding: 38px 32px 60px;
            background: #f5f8f9;
        }


        /* =========================================
           STATISTICS
        ========================================= */

        .priority-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 40px;
}


        .priority-card {
    min-height: 150px;
    padding: 30px 34px;
    border-radius: 14px;
    border: 1px solid;
    box-sizing: border-box;

    display: flex;
    flex-direction: column;
    justify-content: center;

    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.06);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.priority-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
}


/* Critical */
.priority-card.critical {
    background: #fff5f5;
    border-color: #fecaca;
}

.priority-card.critical .priority-card-title,
.priority-card.critical .priority-card-number {
    color: #dc2626;
}


/* Urgent */
.priority-card.urgent {
    background: #fffaf0;
    border-color: #fed7aa;
}

.priority-card.urgent .priority-card-title,
.priority-card.urgent .priority-card-number {
    color: #d97706;
}


/* Normal */
.priority-card.normal {
    background: #f2fbf6;
    border-color: #bbf7d0;
}

.priority-card.normal .priority-card-title,
.priority-card.normal .priority-card-number {
    color: #16a34a;
}


/* Card title */
.priority-card-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 14px;
}


/* Number */
.priority-card-number {
    font-size: 42px;
    font-weight: 700;
    line-height: 1;
}


/* Description */
.priority-card-description {
    margin-top: 14px;
    font-size: 14px;
    color: #64748b;
}
}


        .critical .priority-card-title,
        .critical .priority-card-number {
            color: #dc2626;
        }


        .urgent .priority-card-title,
        .urgent .priority-card-number {
            color: #d97706;
        }


        .normal .priority-card-title,
        .normal .priority-card-number {
            color: #16a34a;
        }


        /* =========================================
           REQUESTS CONTAINER
        ========================================= */

        .requests-container {
            background: #ffffff;
            border: 1px solid #dbe4e7;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(15, 23, 42, 0.04);
        }


        .requests-header {
            padding: 26px 28px;
            border-bottom: 1px solid #dbe4e7;
            background: #ffffff;
        }


        .requests-title {
            margin: 0;
            color: #111827;
            font-size: 21px;
            font-weight: 700;
        }


        .requests-description {
            margin-top: 7px;
            color: #64748b;
            font-size: 14px;
        }


        /* =========================================
           REQUEST ROW
        ========================================= */

        .request-row {
            padding: 30px 28px;
            border-bottom: 1px solid #e2e8f0;
        }


        .request-row:last-child {
            border-bottom: none;
        }


        .request-grid {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 40px;
            align-items: center;
        }


        .request-heading {
            margin: 0 0 18px 0;
            color: #111827;
            font-size: 19px;
            font-weight: 700;
        }


        .request-detail {
            color: #475569;
            font-size: 15px;
            margin-bottom: 10px;
        }


        .request-detail strong {
            color: #111827;
        }


        .current-priority {
            margin-top: 16px;
            color: #475569;
            font-size: 15px;
        }


        .current-priority strong {
            color: #008c9e;
        }


        /* =========================================
           FORM
        ========================================= */

        .priority-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }


        .priority-label {
            color: #475569;
            font-size: 14px;
            font-weight: 600;
        }


        .priority-select,
        .priority-reason {
            width: 100%;
            box-sizing: border-box;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #111827;
            padding: 12px 14px;
            font-size: 14px;
        }


        .priority-select:focus,
        .priority-reason:focus {
            outline: none;
            border-color: #008c9e;
            box-shadow: 0 0 0 2px rgba(0, 140, 158, 0.10);
        }


        .priority-reason::placeholder {
            color: #94a3b8;
        }


        .priority-button {
            width: 100%;
            border: none;
            border-radius: 8px;
            background: #c91f3d;
            color: #ffffff;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }


        .priority-button:hover {
            background: #b51b37;
        }


        /* =========================================
           SUCCESS MESSAGE
        ========================================= */

        .success-message {
            margin-bottom: 24px;
            padding: 14px 18px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            color: #047857;
            font-size: 14px;
        }


        /* =========================================
           EMPTY STATE
        ========================================= */

        .empty-state {
            padding: 50px;
            text-align: center;
            color: #64748b;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1000px) {

            .priority-stats {
                grid-template-columns: 1fr;
            }


            .request-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            .emergency-page {
                padding: 25px 16px 40px;
            }

        }

    </style>


    <div class="emergency-page">


        {{-- SUCCESS MESSAGE --}}

        @if (session('success'))

            <div class="success-message">
                {{ session('success') }}
            </div>

        @endif


        {{-- =========================================
             PRIORITY STATISTICS
        ========================================= --}}

        <div class="priority-stats">


            {{-- Critical --}}

            <div class="priority-card critical">

                <div class="priority-card-title">
                    Critical Requests
                </div>

                <div class="priority-card-number">
                    {{ $criticalCount }}
                </div>

                <div class="priority-card-description">
                    Highest priority
                </div>

            </div>


            {{-- Urgent --}}

            <div class="priority-card urgent">

                <div class="priority-card-title">
                    Urgent Requests
                </div>

                <div class="priority-card-number">
                    {{ $urgentCount }}
                </div>

                <div class="priority-card-description">
                    Requires quick action
                </div>

            </div>


            {{-- Normal --}}

            <div class="priority-card normal">

                <div class="priority-card-title">
                    Normal Requests
                </div>

                <div class="priority-card-number">
                    {{ $normalCount }}
                </div>

                <div class="priority-card-description">
                    Standard priority
                </div>

            </div>


        </div>


        {{-- =========================================
             EMERGENCY REQUESTS
        ========================================= --}}

        <div class="requests-container">


            <div class="requests-header">

                <h3 class="requests-title">
                    Emergency Blood Requests
                </h3>

                <p class="requests-description">
                    Critical requests are displayed first.
                </p>

            </div>


            @forelse ($requests as $emergencyRequest)


                <div class="request-row">


                    <div class="request-grid">


                        {{-- REQUEST INFORMATION --}}

                        <div>

                            <h4 class="request-heading">
                                Emergency Request #{{ $emergencyRequest->id }}
                            </h4>


                            <div class="request-detail">

                                Blood Group:

                                <strong>
                                    {{ $emergencyRequest->blood_group }}
                                </strong>

                            </div>


                            <div class="request-detail">

                                Status:

                                <strong>
                                    {{ ucfirst($emergencyRequest->status) }}
                                </strong>

                            </div>


                            <div class="current-priority">

                                Current Priority:

                                <strong>
                                    {{ ucfirst($emergencyRequest->priority) }}
                                </strong>

                            </div>

                        </div>


                        {{-- PRIORITY UPDATE FORM --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.emergency-priority.update',
                                $emergencyRequest
                            ) }}"
                            class="priority-form"
                        >

                            @csrf

                            @method('PATCH')


                            <label class="priority-label">
                                Change Priority
                            </label>


                            <select
                                name="priority"
                                class="priority-select"
                                required
                            >

                                <option
                                    value="normal"
                                    @selected(
                                        $emergencyRequest->priority === 'normal'
                                    )
                                >
                                    Normal
                                </option>


                                <option
                                    value="urgent"
                                    @selected(
                                        $emergencyRequest->priority === 'urgent'
                                    )
                                >
                                    Urgent
                                </option>


                                <option
                                    value="critical"
                                    @selected(
                                        $emergencyRequest->priority === 'critical'
                                    )
                                >
                                    Critical
                                </option>

                            </select>


                            <input
                                type="text"
                                name="priority_reason"
                                class="priority-reason"
                                placeholder="Reason for priority"
                            >


                            <button
                                type="submit"
                                class="priority-button"
                            >
                                Update Priority
                            </button>


                        </form>


                    </div>


                </div>


            @empty


                <div class="empty-state">
                    No emergency blood requests found.
                </div>


            @endforelse


        </div>


    </div>


</x-app-layout>