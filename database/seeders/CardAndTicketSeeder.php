<?php

namespace Database\Seeders;

use App\Models\CardAndTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardAndTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample card and ticket types
        $data = [
            ['name' => 'Ticket', 'type' => 'ticket', 'status' => 'active'],
            ['name' => 'Pledge Card', 'type' => 'card', 'status' => 'active'],
            ['name' => 'Pledge Card With Name', 'type' => 'card', 'status' => 'inactive'],
            ['name' => 'Pledge Card With Link', 'type' => 'card', 'status' => 'active'],

        ];

        // Insert data into cards_and_tickets table
        foreach ($data as $item) {
            CardAndTicket::create($item);
        }
    }
}
