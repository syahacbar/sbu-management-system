<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuScheme;
use App\Models\MasterSbuSubclassification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sbu.local'],
            [
                'name' => 'Admin SBU',
                'password' => Hash::make('password'),
            ],
        );

        $this->seedMasterKblis();
        $this->seedMasterSbuClassifications();
        $this->seedMasterSbuSchemes();
        $this->seedGenericMasterReferences();
        $this->seedCompaniesAndWorkspaces();
    }

    private function seedMasterKblis(): void
    {
        foreach ([
            ['code' => '41011', 'name' => 'Konstruksi Gedung Hunian', 'sort_order' => 10],
            ['code' => '41012', 'name' => 'Konstruksi Gedung Perkantoran', 'sort_order' => 20],
            ['code' => '42101', 'name' => 'Konstruksi Jalan Raya', 'sort_order' => 30],
            ['code' => '42201', 'name' => 'Konstruksi Jaringan Irigasi', 'sort_order' => 40, 'is_active' => false],
        ] as $item) {
            MasterKbli::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'description' => 'Data dummy KBLI untuk pengujian aplikasi.',
                    'is_active' => $item['is_active'] ?? true,
                    'sort_order' => $item['sort_order'],
                ],
            );
        }
    }

    private function seedMasterSbuClassifications(): void
    {
        $classifications = [
            ['code' => 'BG', 'name' => 'Bangunan Gedung', 'sort_order' => 10],
            ['code' => 'BS', 'name' => 'Bangunan Sipil', 'sort_order' => 20],
            ['code' => 'SI', 'name' => 'Spesialis', 'sort_order' => 30],
        ];

        foreach ($classifications as $item) {
            $classification = MasterSbuClassification::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'description' => 'Data dummy klasifikasi SBU.',
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ],
            );

            foreach ($this->subclassificationsFor($item['code']) as $subItem) {
                MasterSbuSubclassification::updateOrCreate(
                    ['code' => $subItem['code']],
                    [
                        'master_sbu_classification_id' => $classification->id,
                        'name' => $subItem['name'],
                        'description' => 'Data dummy subklasifikasi SBU.',
                        'is_active' => $subItem['is_active'] ?? true,
                        'sort_order' => $subItem['sort_order'],
                    ],
                );
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function subclassificationsFor(string $classificationCode): array
    {
        return match ($classificationCode) {
            'BG' => [
                ['code' => 'BG001', 'name' => 'Konstruksi Gedung Hunian', 'sort_order' => 10],
                ['code' => 'BG002', 'name' => 'Konstruksi Gedung Perkantoran', 'sort_order' => 20],
            ],
            'BS' => [
                ['code' => 'BS001', 'name' => 'Konstruksi Jalan Raya', 'sort_order' => 10],
                ['code' => 'BS002', 'name' => 'Konstruksi Jembatan', 'sort_order' => 20],
            ],
            default => [
                ['code' => 'SI001', 'name' => 'Pekerjaan Persiapan', 'sort_order' => 10],
                ['code' => 'SI002', 'name' => 'Pekerjaan Pembongkaran', 'sort_order' => 20, 'is_active' => false],
            ],
        };
    }

    private function seedGenericMasterReferences(): void
    {
        $tables = [
            'master_qualifications' => [
                ['code' => 'K', 'name' => 'Kecil'],
                ['code' => 'M', 'name' => 'Menengah'],
                ['code' => 'B', 'name' => 'Besar'],
            ],
            'master_lsbus' => [
                ['code' => 'LSBU-01', 'name' => 'LSBU Nusantara'],
                ['code' => 'LSBU-02', 'name' => 'LSBU Konstruksi Prima'],
            ],
            'master_associations' => [
                ['code' => 'ASKONAS', 'name' => 'Asosiasi Kontraktor Nasional'],
                ['code' => 'GAPENSI', 'name' => 'Gabungan Pelaksana Konstruksi Nasional Indonesia'],
            ],
            'master_science_fields' => [
                ['code' => 'SIPIL', 'name' => 'Teknik Sipil'],
                ['code' => 'ARS', 'name' => 'Arsitektur'],
            ],
            'master_bg_equipment' => [
                ['code' => 'BG-ALAT-01', 'name' => 'Concrete Mixer'],
                ['code' => 'BG-ALAT-02', 'name' => 'Scaffolding'],
            ],
            'master_bs_equipment' => [
                ['code' => 'BS-ALAT-01', 'name' => 'Excavator'],
                ['code' => 'BS-ALAT-02', 'name' => 'Vibro Roller'],
            ],
            'master_balance_items' => [
                ['code' => 'ASSET', 'name' => 'Total Aset'],
                ['code' => 'MODAL', 'name' => 'Modal Bersih'],
            ],
            'master_document_templates' => [
                ['code' => 'SPTJM', 'name' => 'Template SPTJM'],
                ['code' => 'NERACA', 'name' => 'Template Neraca'],
            ],
        ];

        foreach ($tables as $table => $items) {
            foreach ($items as $index => $item) {
                DB::table($table)->updateOrInsert(
                    ['code' => $item['code']],
                    [
                        'name' => $item['name'],
                        'description' => 'Data dummy referensi global.',
                        'is_active' => $item['is_active'] ?? true,
                        'sort_order' => ($index + 1) * 10,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }
    }

    private function seedMasterSbuSchemes(): void
    {
        $schemes = [
            ['scheme_code' => 'SK-BG001-K', 'scheme_name' => 'Skema Konstruksi Gedung Hunian Kecil', 'kbli_code' => '41011', 'classification_code' => 'BG', 'subclassification_code' => 'BG001', 'qualification' => 'Kecil', 'sort_order' => 10],
            ['scheme_code' => 'SK-BG002-M', 'scheme_name' => 'Skema Konstruksi Gedung Perkantoran Menengah', 'kbli_code' => '41012', 'classification_code' => 'BG', 'subclassification_code' => 'BG002', 'qualification' => 'Menengah', 'sort_order' => 20],
            ['scheme_code' => 'SK-BS001-B', 'scheme_name' => 'Skema Konstruksi Jalan Raya Besar', 'kbli_code' => '42101', 'classification_code' => 'BS', 'subclassification_code' => 'BS001', 'qualification' => 'Besar', 'sort_order' => 30],
        ];

        foreach ($schemes as $item) {
            $kbli = MasterKbli::where('code', $item['kbli_code'])->first();
            $classification = MasterSbuClassification::where('code', $item['classification_code'])->first();
            $subclassification = MasterSbuSubclassification::where('code', $item['subclassification_code'])->first();

            if (! $kbli || ! $classification || ! $subclassification) {
                continue;
            }

            MasterSbuScheme::updateOrCreate(
                ['scheme_code' => $item['scheme_code']],
                [
                    'master_kbli_id' => $kbli->id,
                    'master_sbu_classification_id' => $classification->id,
                    'master_sbu_subclassification_id' => $subclassification->id,
                    'scheme_name' => $item['scheme_name'],
                    'qualification' => $item['qualification'],
                    'description' => 'Data dummy skema SBU untuk uji coba.',
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ],
            );
        }
    }

    private function seedCompaniesAndWorkspaces(): void
    {
        $companies = [
            [
                'name' => 'PT Maju Konstruksi Papua',
                'nib' => '9120100012345',
                'npwp' => '01.234.567.8-901.000',
                'email' => 'admin@majupapua.test',
                'phone' => '0967-555-0101',
                'address' => 'Jl. Raya Abepura No. 10, Jayapura',
            ],
            [
                'name' => 'CV Cendrawasih Bangun Persada',
                'nib' => '9120100098765',
                'npwp' => '09.876.543.2-901.000',
                'email' => 'kontak@cendrawasih.test',
                'phone' => '0967-555-0202',
                'address' => 'Jl. Trikora No. 25, Jayapura',
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::updateOrCreate(
                ['nib' => $companyData['nib']],
                [
                    ...$companyData,
                    'is_active' => true,
                    'description' => 'Data dummy perusahaan untuk uji coba workspace.',
                ],
            );

            $this->seedWorkspaceRecords($company);
        }
    }

    private function seedWorkspaceRecords(Company $company): void
    {
        $records = [
            'directors' => [
                ['code' => 'DIR-001', 'name' => 'Yohanes Pratama', 'status' => 'aktif'],
            ],
            'pjbus' => [
                ['code' => 'PJBU-001', 'name' => 'Maria Elisabeth', 'status' => 'aktif'],
            ],
            'applications' => [
                ['code' => 'SBU-2026-001', 'name' => 'Pengajuan SBU Bangunan Gedung', 'status' => 'draft', 'record_date' => '2026-07-09'],
                ['code' => 'SBU-2026-002', 'name' => 'Pengajuan SBU Bangunan Sipil', 'status' => 'review', 'record_date' => '2026-07-10'],
            ],
            'pjtbus' => [
                ['code' => 'PJTBU-001', 'name' => 'Andi Wijaya', 'status' => 'aktif'],
            ],
            'pjskbus' => [
                ['code' => 'PJSKBU-001', 'name' => 'Ratna Sari', 'status' => 'aktif'],
            ],
            'experts' => [
                ['code' => 'TA-001', 'name' => 'Budi Santoso - Teknik Sipil', 'status' => 'aktif'],
                ['code' => 'TA-002', 'name' => 'Lestari Putri - Arsitektur', 'status' => 'aktif'],
            ],
            'equipment' => [
                ['code' => 'EQ-001', 'name' => 'Excavator Komatsu PC200', 'status' => 'milik sendiri', 'amount' => 850000000],
                ['code' => 'EQ-002', 'name' => 'Concrete Mixer 350L', 'status' => 'sewa', 'amount' => 45000000],
            ],
            'balanceEntries' => [
                ['code' => 'NER-001', 'name' => 'Total Aset 2025', 'status' => 'valid', 'record_date' => '2025-12-31', 'amount' => 2500000000],
                ['code' => 'NER-002', 'name' => 'Modal Bersih 2025', 'status' => 'valid', 'record_date' => '2025-12-31', 'amount' => 1200000000],
            ],
            'documents' => [
                ['code' => 'DOC-001', 'name' => 'Akta Pendirian', 'status' => 'lengkap'],
                ['code' => 'DOC-002', 'name' => 'NPWP Perusahaan', 'status' => 'lengkap'],
            ],
            'archives' => [
                ['code' => 'ARS-001', 'name' => 'Arsip Pengajuan Simulasi', 'status' => 'draft'],
            ],
        ];

        foreach ($records as $relation => $items) {
            foreach ($items as $index => $item) {
                $company->{$relation}()->updateOrCreate(
                    ['code' => $item['code']],
                    [
                        'name' => $item['name'],
                        'status' => $item['status'],
                        'record_date' => $item['record_date'] ?? null,
                        'amount' => $item['amount'] ?? null,
                        'is_active' => $item['is_active'] ?? true,
                        'sort_order' => ($index + 1) * 10,
                        'description' => 'Data dummy workspace milik '.$company->name.'.',
                    ],
                );
            }
        }
    }
}
