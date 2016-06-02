<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Sentry;

class UserTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('users')->truncate();

        $user = Sentry::getUserProvider()->create(array(
        	'email' => 'admin@insomnia.pt',
        	'username' => 'admin',
        	'password' => 'admin',
        	'first_name' => 'O',
        	'last_name' => 'Administrador',
        	'activated' => 1
        ));

        $group = Sentry::getGroupProvider()->findById(1);
		$user->addGroup($group);
        $user->save();

    }

}