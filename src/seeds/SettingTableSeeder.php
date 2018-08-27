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
        $setting->value = '{"title":"CMS", "subtitle":"Demo"}';
        $setting->id_parent = 0;
        $setting->system = 1;
        $setting->order = 1;
        $setting->save();

        $setting = new Setting;
        $setting->id = 2;
        $setting->name = 'super_group';
        $setting->description = 'Ativa permissões extra para grupos com a permissão "admin" e oculta-os do sistema para os restantes grupos.';
        $setting->value = '1';
        $setting->id_parent = 0;
        $setting->system = 1;
        $setting->order = 2;
        $setting->save();

		$setting = new Setting;
        $setting->id = 3;
        $setting->name = 'page_global';
        $setting->description = 'Disponibiliza botão "Definições Globais" na gestão de páginas';
        $setting->value = '{"active":"0", "pagetype_id":"0"}';
        $setting->id_parent = 0;
        $setting->system = 1;
        $setting->order = 3;
        $setting->save();

        $setting = new Setting;
        $setting->id = 4;
        $setting->name = 'languages';
        $setting->description = 'Idiomas disponíveis no site e para tradução';
        $setting->value = '{"pt":"Português"}';
        $setting->id_parent = 0;
        $setting->system = 1;
        $setting->order = 4;
        $setting->save();


    }

}
