<?php

return [

    'groups' => [
        'documents' => [
            'label' => 'Documents',
            'permissions' => [
                'documents.view' => 'View documents',
                'documents.create' => 'Create document',
                'documents.edit' => 'Edit document',
                'documents.delete' => 'Delete document',
            ],
        ],
        'files' => [
            'label' => 'Files',
            'permissions' => [
                'files.upload' => 'Upload file',
                'files.download' => 'Download file',
                'files.delete' => 'Delete file',
            ],
        ],
        'users' => [
            'label' => 'Users',
            'permissions' => [
                'users.manage' => 'Manage users',
            ],
        ],
        'roles' => [
            'label' => 'Roles & Permissions',
            'permissions' => [
                'roles.manage' => 'Manage roles & permissions',
            ],
        ],
        'settings' => [
            'label' => 'Settings',
            'permissions' => [
                'settings.manage' => 'Manage settings',
            ],
        ],
        'audit' => [
            'label' => 'Audit Log',
            'permissions' => [
                'audit.view' => 'View audit log',
            ],
        ],
    ],

];
