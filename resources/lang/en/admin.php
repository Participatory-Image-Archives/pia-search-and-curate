<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'last_login_at' => 'Last login',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
                
            //Belongs to many relations
            'roles' => 'Roles',
                
        ],
    ],

    'image' => [
        'title' => 'Images',

        'actions' => [
            'index' => 'Images',
            'create' => 'New Image',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'salsah_id' => 'Salsah',
            'oldnr' => 'Oldnr',
            'signature' => 'Signature',
            'title' => 'Title',
            'original_title' => 'Original title',
            'file_name' => 'File name',
            'original_file_name' => 'Original file name',
            'salsah_date' => 'Salsah date',
            'sequence_number' => 'Sequence number',
            'location' => 'Location',
            'collection' => 'Collection',
            'verso' => 'Verso',
            'objecttype' => 'Objecttype',
            'model' => 'Model',
            'format' => 'Format',
            
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];