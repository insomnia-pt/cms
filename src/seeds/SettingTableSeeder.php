<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\Setting as Setting;

class SettingTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('settings')->truncate();

        $setting = new Setting;
        $setting->id = 1;
        $setting->name = 'general';
        $setting->description = 'Geral';
        $setting->value = '{"title":"OCMS", "subtitle":"Demo"}';
        $setting->id_parent = 0;
        $setting->system = 1;
        $setting->order = 1;
        $setting->save();

        $setting = new Setting;
        $setting->id = 2;
        $setting->name = 'super_user';
        $setting->description = 'Torna o grupo "Administradores" como grupo super com super poderes e oculta-o do sistema';
        $setting->value = '1';
        $setting->id_parent = 0;
        $setting->system = 1;
        $setting->order = 2;
        $setting->save();

    }

}