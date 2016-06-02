# INSOMNIA CMS

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

Access the `/cms` URL and login with:


username: **admin** / password: **admin**
