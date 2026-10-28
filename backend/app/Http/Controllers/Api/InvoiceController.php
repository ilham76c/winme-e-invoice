<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Menampilkan daftar invoice dengan pencarian,
     * filter status, dan pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::query()
            ->with('paymentMethod')
            ->withCount('items')
            ->when(
                $request->filled('search'),
                function (Builder $query) use ($request): void {
                    $search = $request->string('search')
                        ->trim()
                        ->toString();

                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query->where(
                                'invoice_number',
                                'ilike',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'recipient_name',
                                'ilike',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'ilike',
                                "%{$search}%"
                            );
                        }
                    );
                }
            )
            ->when(
                $request->filled('status'),
                function (Builder $query) use ($request): void {
                    $query->where(
                        'status',
                        $request->input('status')
                    );
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $items = $invoices->getCollection()
            ->map(
                fn (Invoice $invoice): array =>
                    (new InvoiceResource($invoice))->resolve()
            )
            ->values()
            ->all();

        return ApiResponse::success(
            ['Daftar invoice berhasil diambil.'],
            [
                'items' => $items,
                'pagination' => [
                    'current_page' => $invoices->currentPage(),
                    'per_page' => $invoices->perPage(),
                    'total' => $invoices->total(),
                    'last_page' => $invoices->lastPage(),
                ],
            ],
        );
    }

    /**
     * Membuat invoice beserta item-itemnya.
     */
    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $data = $request->validated();

        $invoice = DB::transaction(function () use ($data): Invoice {
            $preparedItems = collect($data['items'])
                ->map(function (array $item): array {
                    $unitPriceCents = (int) round(
                        (float) $item['unit_price'] * 100
                    );

                    $lineTotalCents = $unitPriceCents
                        * (int) $item['quantity'];

                    return [
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'unit_price' => number_format(
                            $unitPriceCents / 100,
                            2,
                            '.',
                            ''
                        ),
                        'line_total' => number_format(
                            $lineTotalCents / 100,
                            2,
                            '.',
                            ''
                        ),
                    ];
                });

            $totalCents = $preparedItems->sum(
                fn (array $item): int => (int) round(
                    (float) $item['line_total'] * 100
                )
            );

            $totalAmount = number_format(
                $totalCents / 100,
                2,
                '.',
                ''
            );

            $invoice = Invoice::create([
                'invoice_number' => 'INV-'
                    . now()->format('Ymd')
                    . '-'
                    . Str::upper(Str::random(8)),
                'recipient_name' => $data['recipient_name'],
                'whatsapp' => $data['whatsapp'],
                'email' => $data['email'],
                'address' => $data['address'] ?? null,
                'payment_method_id' => $data['payment_method_id'],
                'expires_at' => $data['expires_at'],
                'status' => 'active',
                'public_token' => (string) Str::uuid(),
                'subtotal' => $totalAmount,
                'total_amount' => $totalAmount,
            ]);

            $invoice->items()->createMany(
                $preparedItems->all()
            );

            return $invoice;
        });

        $invoice->load(['items', 'paymentMethod']);

        return ApiResponse::success(
            ['Invoice berhasil dibuat.'],
            (new InvoiceResource($invoice))->resolve(),
            201,
        );
    }

    /**
     * Menampilkan detail invoice berdasarkan ID.
     */
    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load(['items', 'paymentMethod']);

        return ApiResponse::success(
            ['Detail invoice berhasil diambil.'],
            (new InvoiceResource($invoice))->resolve(),
        );
    }

    /**
     * Menampilkan invoice melalui public token.
     */
    public function showPublic(string $token): JsonResponse
    {
        $invoice = Invoice::query()
            ->where('public_token', $token)
            ->with(['items', 'paymentMethod'])
            ->first();

        if (! $invoice) {
            return ApiResponse::error(
                ['Invoice tidak ditemukan.'],
                404,
            );
        }

        return ApiResponse::success(
            ['Invoice berhasil diambil.'],
            (new InvoiceResource($invoice))->resolve(),
        );
    }
}
