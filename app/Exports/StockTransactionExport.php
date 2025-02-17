<?php
// namespace App\Exports;

// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithHeadings;

// class StockTransactionExport implements FromCollection, WithHeadings, ShouldAutoSize
// {
//     private $transactions;

//     public function __construct($transactions)
//     {
//         $this->transactions = $transactions;
//     }

//     public function collection()
//     {
//         return new Collection($this->transactions->map(function ($transaction) {
//             return [
//                 'Date' => $transaction->transaction_date,
//                 'Item' => optional($transaction->details->first()->item)->trade_name_en ?? 'N/A',
//                 'Transaction Type' => $transaction->transaction_type,
//                 'Quantity' => $transaction->details->quantity ?? 0,
//             ];
//         }));
//     }

//     public function headings(): array
//     {
//         return ['Date', 'Item', 'Transaction Type', 'Quantity'];
//     }
// }

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockTransactionExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return new Collection($this->transactions);

    }

    public function headings(): array
    {
        return ['Date', 'Item', 'Transaction Type', 'Quantity'];
    }
}
