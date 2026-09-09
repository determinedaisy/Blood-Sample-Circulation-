<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo bKash Payment</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #ededed;
            font-family: Arial, sans-serif;
        }
        .checkout {
            width: 100%;
            max-width: 430px;
            overflow: hidden;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .20);
        }
        .warning {
            padding: 12px 18px;
            background: #fff3cd;
            color: #664d03;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
        }
        .brand {
            padding: 24px;
            color: #d12068;
            text-align: center;
            font-size: 30px;
            font-weight: 800;
        }
        .body {
            padding: 30px;
            background: #cc2f70;
            color: #fff;
        }
        .merchant { text-align: center; }
        .merchant h2 { margin: 0 0 7px; font-size: 20px; }
        .amount {
            margin: 22px 0 28px;
            text-align: center;
            font-size: 32px;
            font-weight: 700;
        }
        label {
            display: block;
            margin: 14px 0 7px;
            font-size: 14px;
            font-weight: 700;
        }
        input {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 5px;
            font-size: 16px;
        }
        .hint { margin-top: 7px; font-size: 12px; opacity: .92; }
        .error { margin-top: 7px; color: #fff5a5; font-size: 13px; }
        .buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 25px;
        }
        button {
            padding: 13px;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
        }
        .cancel { background: #eee; color: #333; }
        .confirm { background: #fff; color: #cc2f70; }
        .footer {
            padding: 16px;
            color: #777;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="checkout">
        <div class="warning">DEMO PAYMENT - No real money will be charged</div>
        <div class="brand">bKash Demo</div>

        <div class="body">
            <div class="merchant">
                <h2>Blood Sample Circulation</h2>
                <div>Invoice: {{ $payment->invoice_number }}</div>
            </div>

            <div class="amount">BDT {{ number_format($payment->amount, 2) }}</div>

            <form method="POST" action="{{ route('bkash.mock.confirm', $payment->id) }}">
                @csrf

                <label for="test_number">Demo bKash number</label>
                <input
                    id="test_number"
                    name="test_number"
                    type="text"
                    inputmode="numeric"
                    maxlength="11"
                    value="{{ old('test_number', '01700000000') }}"
                    placeholder="01700000000"
                    required
                >
                @error('test_number')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="test_pin">Demo PIN</label>
                <input
                    id="test_pin"
                    name="test_pin"
                    type="password"
                    inputmode="numeric"
                    maxlength="6"
                    placeholder="Use any 4-6 digits"
                    required
                >
                <div class="hint">Use fake details only. Never enter a real bKash PIN.</div>
                @error('test_pin')
                    <div class="error">{{ $message }}</div>
                @enderror

                <div class="buttons">
                    <button
                        class="cancel"
                        type="submit"
                        formnovalidate
                        formaction="{{ route('bkash.mock.cancel', $payment->id) }}"
                    >Cancel</button>
                    <button class="confirm" type="submit">Confirm payment</button>
                </div>
            </form>
        </div>

        <div class="footer">Local development payment simulator</div>
    </div>
</body>
</html>
