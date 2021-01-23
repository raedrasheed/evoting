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
            'email' => 'raed.rasheed@iugaza.edu.ps',
            'email_verified_at' => '2021-01-01 00:00:01',
            'password' => bcrypt('password'),
			'role' => '1',
			'is_active' => true,
			'description' => 'System Administrator',
			'mobile' => '0599345342',
			'voted' => false,		
        ]);
		
		DB::table('users')->insert([
			'outer_id' => '647',
            'name' => 'Raed Rasheed',
			'photo' => 'imgs\photos\1.jpg',
            'username' => 'raed',
            'email' => 'raed.rasheed@gmail.com',
            'email_verified_at' => '2021-01-01 00:00:01',
            'password' => bcrypt('password'),
			'role' => '2',
			'is_active' => true,
			'description' => 'System Administrator',
			'mobile' => '0599345342',
			'voted' => false,		
        ]);
		
		/*
			Nominees
		*/
		DB::table('nominees')->insert([
            'name' => 'David de Gea',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '3',
			'is_active' => true,
			'description' => 'David de Gea',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Manuel Neuer',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '3',
			'is_active' => true,
			'description' => 'Manuel Neuer',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Liverpool F.C.',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '1',
			'is_active' => true,
			'description' => 'Liverpool F.C.',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Real Madrid F.C.',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '1',
			'is_active' => true,
			'description' => 'Real Madrid F.C.',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Cristiano Ronaldo',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '2',
			'is_active' => true,
			'description' => 'Cristiano Ronaldo',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Lionel Messi',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '2',
			'is_active' => true,
			'description' => 'Lionel Messi',
        ]);
			DB::table('nominees')->insert([
            'name' => 'Robert Lewandowski',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '2',
			'is_active' => true,
			'description' => 'Robert Lewandowski',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Pep Guardiola',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '4',
			'is_active' => true,
			'description' => 'Pep Guardiola',
        ]);
		DB::table('nominees')->insert([
            'name' => 'Zinedine Zidane',
			'photo' => 'imgs\photos\2.jpg',            
			'nominee_list_id' => '4',
			'is_active' => true,
			'description' => 'Zinedine Zidane',
        ]);
		
		/*
			Nominee Lists
		*/
		DB::table('nominee_lists')->insert([
            'name' => '1 - Best Team',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 1, 
			'is_active' => true,
			'description' => '1_-_Best_Team',
        ]);
		DB::table('nominee_lists')->insert([
            'name' => '2 - Best Player',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 1, 
			'is_active' => true,
			'description' => '2_-_Best_Player',
        ]);
		DB::table('nominee_lists')->insert([
            'name' => '3 - Best Goalkeeper',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 1, 
			'is_active' => true,
			'description' => '3_-_Best_Goalkeeper',
        ]);
		DB::table('nominee_lists')->insert([
            'name' => '4 - Best Coach',
			'photo' => 'imgs\photos\1.jpg', 
			'selected_count' => 1, 
			'is_active' => true,
			'description' => '4_-_Best_Coach',
        ]);
    }
}
