<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Seeder;

class LoanTypeSeeder extends Seeder
{
    /**
     * Values below are reasonable starting defaults, not specified by the
     * business — adjust rates/amounts/terms directly in the database (or
     * via a future Loan Type Management admin page) once real policy is set.
     */
    public function run(): void
    {
        $loanTypes = [
            [
                'name' => 'Personal Loan',
                'description' => 'General purpose personal loan',
                'interest_rate' => 10,
                'min_term_months' => 3,
                'max_term_months' => 12,
                'min_amount' => 5000,
                'max_amount' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'Emergency Loan',
                'description' => 'Fast, short-term loan for urgent needs',
                'interest_rate' => 12,
                'min_term_months' => 1,
                'max_term_months' => 6,
                'min_amount' => 1000,
                'max_amount' => 15000,
                'is_active' => true,
            ],
            [
                'name' => 'Salary / Cash Advance Loan',
                'description' => 'Short-term advance against upcoming salary',
                'interest_rate' => 8,
                'min_term_months' => 1,
                'max_term_months' => 2,
                'min_amount' => 1000,
                'max_amount' => 10000,
                'is_active' => true,
            ],
            [
                'name' => 'Business / Microfinance Loan',
                'description' => 'Working capital for small businesses and microenterprises',
                'interest_rate' => 15,
                'min_term_months' => 6,
                'max_term_months' => 24,
                'min_amount' => 20000,
                'max_amount' => 200000,
                'is_active' => true,
            ],
            [
                'name' => 'Educational Loan',
                'description' => 'Tuition and school-related expenses',
                'interest_rate' => 6,
                'min_term_months' => 6,
                'max_term_months' => 24,
                'min_amount' => 10000,
                'max_amount' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'Gadget / Appliance Loan',
                'description' => 'Financing for electronics and home appliances',
                'interest_rate' => 10,
                'min_term_months' => 3,
                'max_term_months' => 12,
                'min_amount' => 3000,
                'max_amount' => 30000,
                'is_active' => true,
            ],
        ];

        foreach ($loanTypes as $type) {
            // Keyed by name so this seeder is safe to re-run without
            // creating duplicates or overwriting an already-approved loan
            // that's referencing an existing row's id.
            LoanType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
