<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->trim()->toString();

        $payments = Payment::with(['user', 'booking', 'logs'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.package.agency', 'booking.user', 'user', 'logs']);

        return view('admin.payments.show', compact('payment'));
    }
}
