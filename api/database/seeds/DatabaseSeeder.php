<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->call('LookupTableSeeder');
        $this->call('PatientsTableSeeder');
        $this->call('PatientRecordsSeeder');
        $this->call('PatientRecordProceduresTable');
        $this->call('AnnouncementSeeder');
        $this->call('ProcedureTypeCategoriesTable');
        $this->call('ProceduresTableSeeder');
    }
}
