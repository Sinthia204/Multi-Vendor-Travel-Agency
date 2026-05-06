<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; }
        .header { display: flex; justify-content: space-between; margin-bottom: 24px; }
        .title { font-size: 24px; font-weight: bold; }
        .meta { font-size: 12px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background: #f9fafb; }
        .total { text-align: right; font-size: 16px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="title">TravelNest Invoice</div>
            <div class="meta">Invoice #: {{ $payment->transaction_id }}</div>
            <div class="meta">Date: {{ $payment->created_at?->format('Y-m-d') }}</div>
        </div>
        <div>
            <div class="meta">Billed To:</div>
            <div>{{ $user->name }}</div>
            <div>{{ $user->email }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Travel Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $booking->package_name }}</td>
                <td>{{ $booking->travel_date?->format('Y-m-d') }}</td>
                <td>{{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total: {{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}
    </div>
</body>
</html>
