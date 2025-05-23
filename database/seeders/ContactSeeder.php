<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\User;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // Grab the first user or create one
        $user = User::create([
            'fullname' => 'Test User',
            'username' => 'testUser',
            'contact_number' => '+190343434',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create 5 dummy contacts for the user
        $contacts = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@gmail.com',
                'phone' => '+2348011111111',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@gmail.com',
                'phone' => '+2348022222222',
            ],
            [
                'name' => 'Clara Mendez',
                'email' => 'clara@gmail.com',
                'phone' => '+2348033333333',
            ],
            [
                'name' => 'David Oyelowo',
                'email' => 'david@gmail.com',
                'phone' => '+2348044444444',
            ],
            [
                'name' => 'Emily Kings',
                'email' => 'emily@gmail.com',
                'phone' => '+2348055555555',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create(array_merge($contact, ['user_id' => $user->id]));
        }
    }
}
