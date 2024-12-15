<?php

namespace App\Http\Controllers;

use App\Services\PayPalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $validatedData = $request->validate([
            'cart' => 'required|array',
            'total' => 'required|numeric',
        ]);

        $client = PayPalClient::client();

        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');
        $orderRequest->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'PHP',
                        'value' => $validatedData['total'],
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => route('payment.cancel'),
                'return_url' => route('payment.success'),
            ],
        ];

        try {
            $response = $client->execute($orderRequest);

            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect()->away($link->href);
                }
            }

            return back()->withErrors(['error' => 'Unable to process payment.']);
        } catch (\Exception $e) {
            Log::error('Error creating PayPal order: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment creation failed. Please try again later.']);
        }
    }

    public function capturePayment(Request $request)
    {
        $orderID = $request->input('orderID'); 

        if (!$orderID) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order ID is required.',
            ], 400);
        }

        $client = PayPalClient::client();

        try {
            $captureRequest = new OrdersCaptureRequest($orderID);
            $response = $client->execute($captureRequest);

            if ($response->statusCode === 201) {
              

                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment captured successfully!',
                    'data' => $response->result,
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to capture payment.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error capturing PayPal payment: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the payment.',
            ], 500);
        }
    }
}
