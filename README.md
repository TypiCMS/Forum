# Forum

A forum with categories, discussions and posts. Currently in Beta.

## Installation

1. Install TypiCMS
2. Install the forum package: `composer require typicms/forum`
3. Add the `TypiCMS\Modules\Forum\Providers\ModuleServiceProvider::class,` in config/app.php
4. Run `php artisan migrate`
5. Publish the blade views and the scss file: `php artisan vendor:publish --provider="TypiCMS\Modules\Forum\Providers\ModuleServiceProvider"`
6. Add `@import 'public/forum';` in `resources/scss/public.scss`
7. Publish translations `php artisan translations:add vendor/typicms/forum/src/resources/lang`
8. run `npm run dev`
9. Go to the admin side of you TypiCMS project (/admin)
10. Create some forum categories.
11. Create a page linked to the Forum module and navigate to this page on the public side.

This module is part of [TypiCMS](https://github.com/TypiCMS/Base), a multilingual CMS based on the [Laravel framework](https://github.com/laravel/framework).
