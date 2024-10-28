<?php
namespace App\Exports;

use App\Models\Settlement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Carbon; 
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class SettlementReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactionData;

    /**
     * Create a new instance with the given data.
     *
     * @param \Illuminate\Support\Collection $transactionData
     */
    public function __construct($transactionData)
    {
        $this->transactionData = $transactionData;
    }

    /**
     * Return a collection of data for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactionData;
    }

    /**
     * Return the headings for the export file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Merchant Name',
            'Settlement ID',
            'Report Time',
            'Success Transaction Count',
            'Total Transaction Amount',
            'Merchant Fee',
            'GST Amount',
            'Settlement Amount',
            'Created At'
        ];
    }

    /**
     * Map the data for each row in the export file.
     *
     * @param  \App\Models\Settlement  $settlement
     * @return array
     */
    public function map($settlement): array
    {
        return [
            $settlement->business_name,
            $settlement->report_id,
            $settlement->report_time,
            $settlement->success_txn_count,
            $settlement->total_txn_amount,
            $settlement->fee_amount,
            $settlement->tax_amount,
            $settlement->settlement_amount,
            $settlement->created_at,
        ];
    }
}
