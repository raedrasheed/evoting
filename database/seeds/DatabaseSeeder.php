<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		DB::table('users')->insert([
			'outer_id' => '647',
            'name' => 'Raed Rasheed',
			'photo' => 'imgs\photos\1.jpg',
            'username' => 'rrasheed',
            'password' => bcrypt('password'),
			'role' => '1',
			'is_active' => true,
			'description' => 'System Administrator',
			'mobile' => '0599345342',
			'voted' => false,		
        ]);
		
		DB::table('users')->insert([
			'outer_id' => '001',
            'name' => 'Dr. Naser Farahat',
			'photo' => 'imgs\photos\2.jpg',
            'username' => 'nfarahat',
            'password' => bcrypt('password'),
			'role' => '2',
			'is_active' => true,
			'description' => 'Good man',
			'mobile' => '0566345342',
			'voted' => false,
        ]);
		
		DB::table('nominees')->insert([
            'name' => 'Raed Rasheed',
			'photo' => 'imgs\photos\1.jpg',            
			'nominee_list_id' => '1',
			'is_active' => true,
			'description' => 'Good man',
        ]);
		
		DB::table('nominees')->insert([
            'name' => 'Naser Farahat',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '2',
			'is_active' => true,
			'description' => 'Good man',
        ]);
		DB::table('nominee_lists')->insert([
            'name' => 'Presidential',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 3, 
			'is_active' => true,
			'description' => 'Presidential List',
        ]);
		DB::table('nominee_lists')->insert([
            'name' => 'Academic',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 3, 
			'is_active' => true,
			'description' => 'Academic List',
        ]);
		DB::table('nominee_lists')->insert([
            'name' => 'Administrative',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 3, 
			'is_active' => true,
			'description' => 'Administrative List',
        ]);
    }
}
