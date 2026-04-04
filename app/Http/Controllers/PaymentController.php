<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Payment $payment): View
    {
        $payment->load('order');

        return view('payments.show', compact('payment'));
    }
}
