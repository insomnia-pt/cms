<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\DatasourceFieldtype as DatasourceFieldtype;

class DatasourcefieldtypeTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('datasources_fieldtypes')->truncate();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Inteiro';
        $datasourcefieldtype->type = 'integer';
        $datasourcefieldtype->config = '{"field":"number"}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Texto';
        $datasourcefieldtype->type = 'text';
        $datasourcefieldtype->config = '{"field":"text"}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Data';
        $datasourcefieldtype->type = 'date';
        $datasourcefieldtype->config = '{"field":"date"}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Data / Hora';
        $datasourcefieldtype->type = 'dateTime';
        $datasourcefieldtype->config = '{"field":"datetime"}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Editor Texto';
        $datasourcefieldtype->type = 'text';
        $datasourcefieldtype->config = '{"field":"textarea"}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Imagem';
        $datasourcefieldtype->type = 'text';
        $datasourcefieldtype->config = '{"field":"image","parameters":["limit"]}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Documento';
        $datasourcefieldtype->type = 'text';
        $datasourcefieldtype->config = '{"field":"document","parameters":["limit"]}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'ComboBox';
        $datasourcefieldtype->type = 'text';
        $datasourcefieldtype->config = '{"field":"combobox","parameters":["values"]}';
        $datasourcefieldtype->save();

        $datasourcefieldtype = new DatasourceFieldtype;
        $datasourcefieldtype->name = 'Tags';
        $datasourcefieldtype->type = 'text';
        $datasourcefieldtype->config = '{"field":"tags","parameters":["limit"]}';
        $datasourcefieldtype->save();
    }

}