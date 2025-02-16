<?php

namespace App\Exports;

use App\Models\StockTransactionDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class StockTransactionExport implements FromQuery, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return StockTransactionDetail::query()
            ->with('item', 'stockTransaction')
            ->when($this->request->item_id, function ($query) {
                $query->where('item_id', $this->request->item_id);
            })
            ->when($this->request->transaction_type, function ($query) {
                $query->whereHas('stockTransaction', function ($q) {
                    $q->where('transaction_type', $this->request->transaction_type);
                });
            })
            ->when($this->request->start_date && $this->request->end_date, function ($query) {
                $query->whereHas('stockTransaction', function ($q) {
                    $q->whereBetween('transaction_date', [$this->request->start_date, $this->request->end_date]);
                });
            })
            ->select([
                'item_id',
                'quantity',
                'remarks',
            ]);
    }

    public function headings(): array
    {
        return ['Item ID', 'Quantity', 'Remarks'];
    }
}
