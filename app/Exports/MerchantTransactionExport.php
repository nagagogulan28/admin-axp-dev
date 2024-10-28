<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;

class MerchantTransactionExport implements FromCollection , WithHeadings
{
    use Exportable;
    public $transactionData;

    public function __construct($transactiondata){
        $this->transactionData = $transactiondata;
    }

    public function headings(): array
    {
        return [
        "S.no", 
        "Aggregator Code",
        "Company Name",
        "Customer Name", 
        "Mail",
        "Mobile No",
        "Txn Summary",
        "TerminalId",
        "Order Id",
        "Appxpay TransactionId",
        "Partner TransactionId",
        "Amount",
        "Total Service Fee ",
        "Partner Service Fee ",
        "Appxpay Profit ",
        "Transaction status",
        "Txn Initiated At",
        "Txn Updated At",
    ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $index = 1;
        return new Collection(array_map(function ($transaction) use (&$index) {
            // dd($transaction);

                $commision_data   = $transaction['commission_details'];
                $order_details   = $transaction['order_details'];
                $businessDetails = $transaction['business_details'];

                $res = !is_null($order_details) ? json_decode($order_details['payload']) : [];
                $email     = $res->mail;
                $mobile_no = $res->mobile;
                list($username, $domain) = explode('@', $email);
                $sublength = strlen($username) <= 2 ? 0 : 2;
                $maskedUsername = str_repeat('x', strlen($username) - $sublength) . substr($username, -2);
                $maskedEmail = $maskedUsername . '@' . $domain;
                list($countryCode, $number) = explode('-', $mobile_no);
                $maskedNumber = str_repeat('x', strlen($number) - 4) . substr($number, 6, 10);
                $maskedMobile = $countryCode . '-' . $maskedNumber;
                $customer_name = $res->customer_name;
                $txn_summary   = $res->txnnote;

                $total_service_fee    = !is_null($commision_data) ? number_format($commision_data['total_servicefee'],3) : '-';
                $partner_service_fee  = !is_null($commision_data) ? number_format($commision_data['partner_flat_fee'],3) : '-';
                $profit               = !is_null($commision_data) ? number_format($commision_data['appxpay_flat_fee'],3) : '-';

            return [
                'S.No' => $index++,
                "Aggregator Code" => '00'.$transaction['aggregator'], 
                "Company Name" => $businessDetails['company_name'],
                'Customer Name' => $customer_name ?? '---',
                'Email' => $maskedEmail ?? '---',
                'Mobile' => $maskedMobile ?? '---',
                "Txn Summary" => $txn_summary,
                'Terminal ID' => $transaction['terminal_id'],
                'Order ID' => $transaction['order_id'],
                'Appxpay TransactionId' => $transaction['appxpay_txnid'],
                'Partner TransactionId' => $transaction['aggregator_txnid'],
                'Amount' => $transaction['amount'],
                'Total Service Fee' => $total_service_fee,
                'Partner Service Fee' => $partner_service_fee,
                'Profit' => $profit,
                'Status' => $this->mapStatus($transaction['status']),
                'Created At' => $transaction['created_at'],
                'Updated At' => $transaction['updated_at'],
            ];
        }, $this->transactionData));

    }

    private function mapStatus($status)
    {
        switch ($status) {
            case 2:
                return 'Success';
            case 0:
                return 'Failed';
            case 4:
                return 'Expired';
            case 3:
                return 'Tampered';
            case 1:
                return 'Pending';
            default:
                return 'Unknown';
        }
    }

    
}
