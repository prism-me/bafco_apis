<?php

namespace App\Interfaces;

interface PaymentInterface{

    public function makePayment($request);
    public function defferredPayment($request);
}

