<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\Eloquent::unguard();
		\DB::statement("SET @@global.sql_mode='';");
		\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->call('Insomnia\Cms\GroupTableSeeder');
		$this->call('Insomnia\Cms\UserTableSeeder');
		$this->call('Insomnia\Cms\Seeds\MenuTableSeeder');
		$this->call('Insomnia\Cms\Seeds\MenuitemTableSeeder');
		$this->call('Insomnia\Cms\PagetypeTableSeeder');
		$this->call('Insomnia\Cms\PageTableSeeder');
		$this->call('Insomnia\Cms\DatasourceTableSeeder');
		$this->call('Insomnia\Cms\DatasourcefieldtypeTableSeeder');
		$this->call('Insomnia\Cms\SettingTableSeeder');

		\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

}