<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\References;

class ReferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new References;
        $data->id = "1";
        $data->code = "overtime_method";
        $data->name = "Salary / 173";
        $data->expression = "(salary / 173) * overtime_duration_total";
        $data->save();

        $data = new References;
        $data->id = "2";
        $data->code = "overtime_method";
        $data->name = "Fixed";
        $data->expression = "1000 * overtime_duration_total";
        $data->save();
    }
}
