<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment {{ $payment->reference }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-semibold">Payment Link</h1>
            <p class="mt-2 text-sm text-gray-600">This is a placeholder payment page for the WhatsApp order flow.</p>

            <div class="mt-6 space-y-2 text-sm">
                <p><strong>Reference:</strong> {{ $payment->reference }}</p>
                <p><strong>Order:</strong> {{ $payment->order?->order_number }}</p>
                <p><strong>Amount:</strong> {{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            </div>
        </div>
    </div>
</body>
</html>
