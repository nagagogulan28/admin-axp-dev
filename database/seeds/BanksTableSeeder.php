<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;
use App\Models\TransactionMode; // Correct model class name

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            ['name' => 'State Bank of India', 'status' => true],
            ['name' => 'Axis Bank', 'status' => true],
            ['name' => 'RBL Bank', 'status' => true],
            ['name' => 'HDFC Bank', 'status' => true],
            ['name' => 'ICICI Bank', 'status' => true],
            ['name' => 'Kotak Mahindra Bank', 'status' => true],
            ['name' => 'IndusInd Bank', 'status' => true],
            ['name' => 'Punjab National Bank', 'status' => true],
            ['name' => 'Bank of Baroda', 'status' => true],
            ['name' => 'Canara Bank', 'status' => true],
            ['name' => 'Union Bank of India', 'status' => true],
        ];

        $transactionModes = [
            ['name' => 'IMPS'],
            ['name' => 'NEFT'],
            ['name' => 'RTGS'],
            ['name' => 'NET BANKING'],
            ['name' => 'UPI']
        ];

        // Seed banks
        foreach ($banks as $bank) {
            Bank::create($bank);
        }

        // Seed transaction modes
        foreach ($transactionModes as $mode) {
            TransactionMode::create($mode); // Adjusted model class name
        }
    }
}