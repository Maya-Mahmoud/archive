<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'System Administrator',
                'description' => 'Full access to all parts of the system',
                'permissions' => ['*'],
            ]
        );

        Role::updateOrCreate(
            ['name' => 'editor'],
            [
                'display_name' => 'Archivist',
                'description' => 'Create and edit documents and upload files',
                'permissions' => [
                    'documents.view',
                    'documents.create',
                    'documents.edit',
                    'files.upload',
                    'files.download',
                ],
            ]
        );

        Role::updateOrCreate(
            ['name' => 'viewer'],
            [
                'display_name' => 'Viewer',
                'description' => 'View documents and download files only',
                'permissions' => [
                    'documents.view',
                    'files.download',
                ],
            ]
        );
    }
}
