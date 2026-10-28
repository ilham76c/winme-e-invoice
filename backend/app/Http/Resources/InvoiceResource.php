<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,

            'recipient' => [
                'name' => $this->recipient_name,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'address' => $this->address,
            ],

            'payment_method' => new PaymentMethodResource(
                $this->whenLoaded('paymentMethod')
            ),

            'items' => $this->whenLoaded('items', fn () =>
                $this->items->map(fn ($item) => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->line_total,
                ])
            ),

            'expires_at' => $this->expires_at?->toDateString(),
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'total_amount' => $this->total_amount,
            'public_url' => url('/api/public/invoices/' . $this->public_token),
            'created_at' => $this->created_at,

        ];
    }
}
