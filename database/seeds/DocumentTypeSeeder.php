<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define sample image data
        $images = [
            ['document_name' => 'Company Pancard', 'document_type' => 'company_pancard'],
            ['document_name' => 'Company GST', 'document_type' => 'company_gst'],
            ['document_name' => 'Bank Statement', 'document_type' => 'bank_statement'],
            ['document_name' => 'Cancel Cheque', 'document_type' => 'cancel_cheque'],
            ['document_name' => 'Certification of Incorporation', 'document_type' => 'certification_incorporation'],
            ['document_name' => 'MOA', 'document_type' => 'moa'],
            ['document_name' => 'AOA', 'document_type' => 'aoa'],
            ['document_name' => 'Auth Signatory Pancard', 'document_type' => 'auth_signatory_pancard'],
            ['document_name' => 'Auth Signatory Aadhar Card', 'document_type' => 'auth_signatory_aadharcard'],
        ];

        // Insert the image data into the 'images' table
        foreach ($images as $image) {
            DB::table('document_types')->insert($image);
        }
    }
}
