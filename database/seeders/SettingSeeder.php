<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'organization_name', 'value' => 'Archive System', 'type' => 'text', 'label' => 'Organization Name'],
            ['key' => 'max_file_size_mb', 'value' => '20', 'type' => 'number', 'label' => 'Max File Size (MB)'],
            ['key' => 'items_per_page', 'value' => '12', 'type' => 'number', 'label' => 'Items Per Page'],
            ['key' => 'contact_email', 'value' => '', 'type' => 'text', 'label' => 'Contact Email'],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => 'general',
                    'label' => $setting['label'],
                ]
            );
        }
    }
}
