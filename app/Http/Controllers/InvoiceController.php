<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function generateInvoice($orderId)
    {
        // Fetch the order and related items for the specific order
        $order = Order::with('orderItems')->findOrFail($orderId);

        // Calculate the order total
        $orderTotal = 0;
        foreach ($order->orderItems as $item) {
            $orderTotal += $item->price * $item->quantity;
        }

        // Prepare data for the PDF view
        $data = [
            'order' => $order,
            'orderTotal' => $orderTotal,
        ];

        // Generate the PDF using the data
        $pdf = Pdf::loadView('invoice.pdf', $data);

        // Return the generated PDF as a download
        return $pdf->download('invoice_' . $order->id . '.pdf');
    }
}
