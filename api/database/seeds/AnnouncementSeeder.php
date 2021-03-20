<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('announcements')->insert([
            'title' => 'Halloween Special 15% Discount',
            'body' => 'This is a halloween special discount, Good for all patients that visits our Clinic within November 2018',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('announcements')->insert([
            'title' => 'Christmas Discount 20% for loyal Patients',
            'body' => 'Discounted prices for all types of procedures, for patients with records / loyal patients of our beloved company',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
