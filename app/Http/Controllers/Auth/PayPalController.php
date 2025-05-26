<?php

namespace App\Http\Controllers;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\{Amount, Item, ItemList, Payer, Payment, PaymentExecution, RedirectUrls, Transaction};
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Reserva;

class PayPalController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        $paypalConfig = config('services.paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential($paypalConfig['client_id'], $paypalConfig['secret'])
        );

        $this->apiContext->setConfig($paypalConfig['settings']);
    }

    public function payWithPayPal(Request $request)
    {
        $reserva = Reserva::findOrFail($request->reserva_id);

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new Amount();
        $amount->setCurrency("EUR")
               ->setTotal($reserva->carro->preco_diario);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setDescription("Reserva de carro: " . $reserva->carro->modelo);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.status'))
                     ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

        try {
            $payment->create($this->apiContext);

            $reserva->payment_id = $payment->getId();
            $reserva->save();

            return redirect()->away($payment->getApprovalLink());
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Erro ao redirecionar para o PayPal.');
        }
    }

    public function paymentStatus(Request $request)
    {
        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            return redirect()->route('reservas.minhas')->with('error', 'Pagamento cancelado.');
        }

        $payment = Payment::get($request->paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        try {
            $result = $payment->execute($execution, $this->apiContext);

            $reserva = Reserva::where('payment_id', $request->paymentId)->first();
            if ($reserva) {
                $reserva->status_pagamento = 'pago';
                $reserva->save();
            }

            return redirect()->route('reservas.minhas')->with('success', 'Pagamento confirmado com sucesso!');
        } catch (\Exception $ex) {
            return redirect()->route('reservas.minhas')->with('error', 'Erro ao confirmar o pagamento.');
        }
    }

    public function paymentCancel()
    {
        return redirect()->route('reservas.minhas')->with('error', 'Pagamento cancelado pelo utilizador.');
    }
}
