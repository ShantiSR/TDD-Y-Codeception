<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
    {
        Eloquent::unguard();

        User::create([
            'email' => 'testuser@mail.com',
            'password' => Hash::make('secret')
        ]);
        // $this->call('UserTableSeeder');
    }

}
