# INSOMNIA CMS

> NOTE: This package is for Laravel 4.2


## INSTALL

Add this to composer.json and run `composer update` :

```json
{
    "require": {
        "insomnia/cms": "dev-master",
        "cartalyst/sentry": "2.1.*"
    }
}
```

Open `config/app.php` and add the following to the `providers` section:

`'Insomnia\Cms\CmsServiceProvider'`

Configure database connection, and then run the command:

```bash
php artisan cms:install
```

Access to `/cms` URL and login with:


username: **admin** / password: **admin**

---

## PAGE TYPES EXAMPLES
### Example of a page that allow config a Datasource (require a controller in the project to get the data and pass to the view)

Add a new record into `pages_types` table and put this JSON on `config` column:

```json

{
    "areas": [
        
        { 
            "name":"datasource", 
            "field": {
                "name":"Datasource",
                "description":"", 
                "datatype":2, 
                "size": 8,
                "admin":1 
            } 
        },
        {
            "name":"view", 
            "field": {
                "name":"View",
                "description":"", 
                "datatype":2, 
                "size": 8,
                "admin":1 
            } 
        },
        {
            "name":"order", 
            "field": {
                "name":"Order by",
                "description":"",
                "datatype":2,
                "size": 8,
                "admin":1 
            } 
        }
    ]
}

```
This allow to create a controller in the laravel project and get items from database like this:

```php
$items = CMS_ModelBuilder::fromTable($page->areas()->datasource)->orderBy($page->areas()->order)->paginate(6);
```
and return to the view:

```php
return View::make($page->areas()->view, compact('page','items'));
```
&nbsp;

### Example of a page with Subtitle, Text content, Image, and allow to choose the view template
Add a new record into `pages_types` table and put this JSON on `config` column:

```json
{
    "areas":[
        {
            "name":"subtitle", 
            "field": {
                "name":"Sub-título",
                "description":"", 
                "datatype":2, 
                "size": 8,
                "multilang": 1
            } 
        },
        {
            "name":"area1",
            "field": {
                "name":"Conteúdo",
                "description":"", 
                "datatype":5, 
                "size": 10,
                "multilang": 1
            }
        },
        {
            "name":"foto", 
            "field":{
                "name":"Foto",
                "description":"",
                "datatype":"10",
                "size": 10, 
                "parameters":{
                    "limit":"1", 
                    "extensions":"jpeg,jpg,png"
                }
            } 
        }
    ],
    "settings":[
        {
            "name":"view", 
            "field": {
                "name":"View",
                "description":"", 
                "datatype":8, 
                "size": 10, 
                "parameters":{
                    "values":"template.page_red, Page Red; template.page_blue, Page Blue"
                } 
            }
        }
    ]
}
```

### Example of a page that creates a Datasource component and associates it with the page
Add a new record into `pages_types` table and put this JSON on `config` column:

```json





```

- areas (fields listed on page edition)
  - name (field identifier - to use on frontend)
  - field (field config)
    - name (label visible in page edition)
    - description (description visible in page edition)
    - datatype (id of the field type - text / combobox / upload / ... - check `datasources_fieldtypes` table)
    - size (bootstrap col size)
    - multilang (if 1, the system allow translate the field)
    - admin (if 1, the field is only visible to admin)
    - parameters (in some cases, the field type require values to choose - like a combobox)
      - values

- settings (fields listed on right side of page edition)
  - name (field identifier - to use on frontend)
  - field (field config)
    - name (label visible in page edition)
    - description (description visible in page edition)
    - datatype (id of the field type - text / combobox / upload / ... - check `datasources_fieldtypes` table)
    - size (bootstrap col size)
    - parameters (in some cases, the field type require values to choose - like a combobox)
      - values


---

## OTHER CONFIGS
#### Show component shortcut on page edition

- In `datasource_page` table add the id of the page `page_id` and the component id `datasource_id`