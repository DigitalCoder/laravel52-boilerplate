Laravel 5.2 Boilerplate
=======================

## Introduction

This package provides the basic frame work to quickly start a new Laravel 5.2 project. It also includes several packages and features that we commonly use. The instructions in this package assume a PHP 7 setup but can be adjusted for other versions.

## Getting Started

This section contains information regarding setting up your project once you have downloaded this boilerplate. Once you complete these steps, you can remove this section from the `README`. Sections below this one should remain in the `README`.

Once you have downloaded this package, take the following steps:

1. Update the `composer.json` file to provide the proper package name, description, license, authors, etc., for your application.
2. Update the `bower.json` file to provide proper package name, authors, etc., for your application.
3. Replace any instances of "{sitename}" in this `README` with a site name/URL to use when accessing the test site.
4. Update the `SITE_URL` varilable value in `setup-mac.sh` to an appropriate URL.
5. Remove this section of the `README`.
6. Continue with the "Installation" section below.

## Installation

1. Install infrastructure dependencies.
  * Node. [Install it here.](https://nodejs.org/)
  * VirtualBox. [Download it here.](https://www.virtualbox.org/wiki/Downloads)
  * Vagrant. [Download it here.](http://www.vagrantup.com/downloads.html)

  You will also need an SSH key on your machine. To check if you have generated a key, enter this command. You should see a file response.

  `ls ~/.ssh/id_rsa`

  If no file is found, you will need to generate a key. To do so, enter this command and follow the instructions:

  `ssh-keygen -t rsa`
  
  You may want to leave the password blank if you don't plan to use the key for any external servers.

2. Check out this repository, if you haven't done so already.

3. Open Terminal or a command prompt, and navigate to the root directory of where you cloned this repository.

4. If you have a Mac:
  1. Run `./setup-mac.sh`.
  2. Skip to the **Using the Development Server** section below.

    *Note: If there's a problem and you need to re-run this script, you MAY need to run these commands:*

    ```bash
    vagrant destroy (possibly with sudo)
    sudo rm -rf ~/VirtualBox\ VMs/{VM_NAME} (Replacing the VM_NAME with the actual VM name)
    ```

5. Install infrastructure dependencies (part 2)!
  * Bower. `npm install -g bower`
  * Vagrant server image (with PHP 7). `vagrant box add laravel/homestead --box-version 0.4.0`

6. Configure Homestead (optional; provides a local development environment).
  - Edit the configuration file Homestead.yaml
  - Change the value for `folders: -> map` to the root directory of this site on your local machine.
  - Record the value under `ip:`, and save and close the file.
  - Add the local domain to your `/etc/hosts` file. On Mac (replacing the IP address with that listed in the Homestead config):

    ```bash
    echo '192.168.10.10    {sitename}.app' | sudo tee -a /private/etc/hosts; dscacheutil -flushcache
    ```

7. Install packages required for both backend and frontend.

  ```bash
  php composer.phar selfupdate
  php composer.phar install
  npm install
  bower install
  ln -s ./node_modules/gulp/bin/gulp.js gulp_local
  ```

8. Set the configuration file.

  The site configuration values are stored in `.env`. This file is excluded from the git repository so that the repository does not contain passwords or other sensitive information. You can either use a provided `.env` file or copy the included `.env.example` to `.env`, and update the values:

  ```
  cp .env.example .env
  ```

9. Regenerate the application key.

  ```
  php artisan key:generate
  ```

10. Set up the local database, if applicable. *Note: Skip this step if you will be connecting to a pre-existing database.*

  If you will be using a local development database, run the following commands to set up base user tables and seed test data. The default migrations contain stock Laravel migrations and some custom user seed data.

  ```
  php artisan migrate
  php artisan db:seed
  ```

## Using the Development Server

To start the local development server, just type `vagrant up` into a Terminal window located at your application root. Then, you can access http://{sitename}.app. When developing, use the `gulp watch` command to handle auto-compilation of SCSS and JS files, plus other build tasks like copying files to their correct locations.

## If Things Are Not Working Right

Try entering these commands into the site root in a Terminal window:

```
php composer.phar install
php composer.phar update
php artisan config:clear
php artisan cache:clear
```

Note that running `php composer.phar update` will update all of your application's PHP dependencies to the most recent versions, which may fix bugs in your dependent libraries, but may also introduce library incompatibilities if you're not mindful of what's happening!

## Gulp Tasks and Source File Locations

`gulp` is set up to perform several services, both for development and deployment. It expects certain file types to be in specific locations. The
following is a breakdown of the functions that Gulp performs:

### SASS

- All site SASS files should be located in the `/resources/assets/sass` directory.
- `gulp` compiles SASS to two files:
  - `/public/build/css/app.css`: This will include application-specific CSS files, which should reside in the `/resources/assets/sass` directory
  - `/public/build/css/vendor.css`: This will include all vendor-included CSS files, specifically from the `/bower_components` directory. Note that you must add each file you want included to `gulpfile.js`.
- To reference these files in Blade template layouts, use the `cdn` and `elixir` commands:

  ```html
  <link rel="stylesheet" href="{{ cdn(elixir('css/app.css')) }}">
  ```

  This will then provide the correct path and version when built.
- Compiled CSS files will automatically be versioned.
- You will generally want to include `vendor.js` first, so that you can override styles as necessary. This provides the correct path and version.
- Source map files will automatically be generated.

### JavaScript

- All site JavaScript files should be located in the `/resources/assets/js` directory.
- `gulp` compiles JavaScript to two files:
  - `/public/build/js/app.js`: This will include application-specific JS files, which should reside in the `/resources/assets/js` directory
  - `/public/build/js/vendor.js`: This will include all vendor-included JS files, primarily from the `/bower_components` directory. Note that you must add each file you want included to `gulpfile.js`. If your script is not included in a Bower plugin, you can place it elsewhere and just continue to reference it in `gulpfile.js`.
- To reference these files in Blade template layouts, use the `cdn` and `elixir` commands:

  ```html
  <script src="{{ cdn(elixir('js/vendor.js')) }}"></script>
  ```

- Compiled JS files will automatically be versioned.
- You will generally want to include `vendor.js` first.
- Source map files will automatically be generated.

### Images

- All images should be located in the `/resources/assets/images` directory.
- `gulp` will version these images and copy them to `/public/build/images`.

### Fonts

- Font files for Glyphicons and Font Awesome will be copied to `/public/build/fonts`.

## Included Packages

The following packages are included with this installation by default:

### Authentication and User Types

The Laravel Auth module automatically provides functionality for login, logout, and password reset. The included database migrations automatically 
create the `users` and `user_logins` tables. There is also support for user types. The default user types are "admin" and "user", but these can be changed in the users table migration. A successful login redirects to the appropriate location based on user type. This can be modified in `App\Http\Controllers\Auth\AuthController::homeRedirect()`.

For testing, two user accounts are created:

- Admin: admin@test.com / admin
- User: user@test.com / user

### JavaScript

- [jQuery](https://jquery.org/)
- [Twitter Bootstrap](http://getbootstrap.com)
- [Bootbox](http://bootboxjs.com/): A bootstrap-based dialog helper.
- [Font Awesome](https://fortawesome.github.io/Font-Awesome/): Because glyphicons are a bit lacking.

### Debugbar

The Laravel Debugbar is useful in development to show statistics on queries, vews, etc. The bar can be disabled by changing `APP_DEBUG` to `false` in `.env`.

### Traits

The following reusable PHP traits have been created:

- `App\Traits\HasStatus`: Adds support for a "status" field to an Eloquent model.

## Additional Functionality

The following functionality has been added by default:

### Google Analytics

- Add your GA property ID to the `.env` file like this:

  ```
  GOOGLE_ANALYTICS_PROPERTY=UA-XXXXXXXX-XX
  ```

- Analytics code is automatically included in `/resources/views/layouts/partials/google-analytics.blade.php`.
- To add custom dimensions, you can define the values in `/app/Providers/ComposerServiceProvider.php`, then add them to the partial view above.
- To send analytics events, there is a JS function available: `clSendAnalyticsEvent(category, action, label, value)`.
- You can add event listeners to send these functions in `/resources/assets/js/widgets/analytcs.js`.

### Blade Template Commands

The following custom commands have been added to Blade templates:

- `datetime(\DateTime object)`: Outputs the date/time like "06/17/2014 17:23"
- `isodate(\DateTime object)`: Outputs the date/time like "2004-02-12T15:19:21+00:00"
- `verbosedate(\DateTime object)`: Outputs the date/time like "Mon, January 7th, 2015"
- `verboseshortdate(\DateTime object)`: Outputs the date/time like "Monday, January 7th"
- `@define $variable = "whatever"`

### Breadcrumbs

Bootstrap-styled breadcrumb navigation. See `/app/Http/breadcrumbs.php` for details.

### Favicon

It's recommended to use [Real Favicon Generator](http://realfavicongenerator.net/) to convert a PNG file to a favicon. Once that's done, the icon files can be placed in `/resources/favicons`, and the HTML markup placed into `/assets/views/layouts/partials/favicons.blade.php`.

### Custom JavaScript Helper Functions

- `clShowLoading()`: Shows a loading overlay. Useful when waiting for an AJAX call to return.
- `clHideLoading()`: Removes the loading overlay.
- `clInitFormListeners(container)`: Listens for changes of the value of all fields within the container. If the user tries to navigate away from the page without saving changes (via form submission or AJAX), they are shown a standard JS `confirm()` navigation message. The container can be a form or a containing element. This will also enable/disable the form's submit button based on the HTML5 validity of the form elements.
- `clResetFieldChangeStatus(field)`: For a field whose values are being tracked via the above function, reverts the value and the field status.

### PHP Helper Functions

- `cdn(filePath)` - Prepends a relative filepath with the CDN prefix defined in `.env`. For example, if `.env` contains `CDN="cdn.causelabs.com/"`, then `cdn("js/custom.js")` would output:

  ```
  //cdn.causelabs.com/js/custom.js
  ```

  This allows CDNs to be specified on a per-installation basis. If no CDN is set in `.env`, the path is set to the site root.
- `field($fieldName, $model = null, $prefix = '')`: Pre-fills a form input with a specific value from session/model.
- `options($optionList, $selectedValue = null)`: Generates HTML for a set of `select` field options.
- `redirectWithValidation($route, $validator, $params = [])`: Produces a `Redirect` object with the appropriate validation message.

### SASS

- The `/resources/assets/sass/lib` folder includes some basic variable definitions and mixins.

### Special CSS Classes

- **.cl-js-delete-button-form**: Use this on delete buttons in forms. This will issue a confirmation prompt before proceeding.
- **.cl-js-link-button**: Any buttons with the class `cl-js-link-button` will function as a link. The URL should be contained in `data-href`.
- **.cl-js-link-button-new**: Same as above, but opens in a new tab.
- **.cl-js-select-on-focus**: Any form field with this class will automatically select all contents on focus or click.
- **.cl-js-ajax-field**: Any field containing this class will automatically be saved via AJAX upon blur, assuming the following prerequisites are met:
    - The CSRF token must be stored in a variable on the page: `var csrfToken="{!! csrf_token() !!}";`
    - The field must have a `data-url` attribute containing the URL to which it should post, e.g.: `data-url="{{ route('admin-workshop-post', ['programId' => $programId, 'id' => $workshop->id]) }}"`
- **.cl-js-delete-button-ajax**: Any button containing this class will automatically delete the record via AJAX after confirming deletion, assuming the following prerequisites are met:
    - The CSRF token must be stored in a variable on the page: `var csrfToken="{!! csrf_token() !!}";`
    - The field must have a `data-url` attribute containing the URL to which it should post: `data-url="{{ route('admin-workshop-post', ['programId' => $programId, 'id' => $workshop->id]) }}"`
    - The field must have a `data-id` attribute containing the record ID
    - The field must have a `data-description` attribute containing the type of record being deleted
- **.cl-print-only**: The element is only displayed when printing.
- **.cl-no-print**: The element is hidden when printing.
- **.cl-js-print-button + .cl-print-button**: The element is styled as a print button (`.cl-print-button`), and will open the print dialog when clicked (`.cl-js-print-button`).
- **.cl-js-no-change**: Any field with this CSS class will not trigger a form change.

### Suggested CSS Naming Convention

We are using a few basic namespacing rules to name our CSS classes, depending on a number of factors. The main format would look like:

```
.[project_initials]-[js|u|is_...|has_...|c|qa]?-[block-name-or-context]__[child_block]--[modifier]
```

For instance, if we are designing CSS rules for a project named "Foo Project", some CSS classes might take the following form:

- **fp-js-validated-form**: A form that will be validated with JavaScript. Please note that classes with the **js** particle should not be used for styling.
- **fp-u-margin-top--large**: This element will have a margin top applied. Please note the modifier **--large** and the *u* particle that means "utility".
- **fp-is_active**: CSS classes that depend on a particular condition should use the particle **is_whatever-state-or-condition**
- **fp-c-component_name**: This is a standalone component that can be reused all over the application without interferences with other UI elements.
- **fp-qa-artificial-hook**: This hook is used solely for QA purposes and does not affect the interface's behavior nor appearance.
- **fp-feedback-container__comment--small**: Any comment within a feedback container, displayed as small in the current context.

While this naming convention is very opinionated and the benefits/drawbacks of applying to its full extent are arguable, we recommend to stick at least to the minimum form `initials-purpose-name`, like `.cl-js-print-button` or `.cl-qa-submit-button`. This will:

1. Prevent conflicts and collisions with third-party libraries.
2. Explicitly state the purpose of the class: **js** for JavaScript hooks, **qa** for QA hooks, **u** for application-wide utility classes, and so forth. 
3. Isolate components in our own interfaces, thus avoiding collisions (again!).

[More Info](http://csswizardry.com/2015/03/more-transparent-ui-code-with-namespaces/)

## Database Seeding

There are PHP classes set up to handle seeding both "domain" data and "test" data as part of Laravel. "Domain" data is data that is required for both production and test environments, such as an administrator user or a list of categories. "Test" data constitutes anything that would be needed on a staging or development server.

- Generate "domain" data in `/database/seeds/RealDataSeeder.php`.
- Generate "test" data in `/database/seeds/TestDataSeeder.php`. Do not duplicate domain data in this class, as both classes are run for test environments.

To seed the data:

- Domain only: `php artisan db:seed --class=RealDataSeeder`
- Domain and test: `php artisan db:seed `

Typically, seeding needs to only happen one time per environment, *unless* the database is destroyed and reprovisioned.

## Deploying to Stage/Prodution

While this boilerplate doesn't contain any deployment configuration out of the box, it is recommended that any deployment script you create include the following commands:

### Pre-Deployment

```bash
php composer.phar selfupdate
php composer.phar install

npm install --production
bower install
bower prune

node_modules/gulp/bin/gulp.js --production

chmod -R g+w bootstrap
chmod -R g+w storage
```

### Deployment

```bash
rsync -avzO -e "ssh" \
--include "***" \
--include "*" \
--include ".htaccess" \
--include "public/.htaccess" \
--exclude "***/.git" \
--exclude "***/.gitignore" \
--exclude "bower_components" \
--exclude "node_modules" \
--exclude "storage/logs/*" \
--exclude "tests" \
--exclude "bower.json" \
--exclude "phpunit.xml" \
--exclude "README.md" \
"$WORKSPACE/" \
$DEPLOYHOST:$DEPLOYPATH
```

where:

- `$WORKSPACE` defines the source directory for your application
- `$DEPLOYHOST` defines the host you're deploying to in standard OpenSSH client form, e.g. "ubuntu@target-server.com"
- `$DEPLOYPATH` defines the path you're deploying into on the target server (must be accessible by the user defined in `$DEPLOYHOST`)

### Post-Deployment

```bash
cd /var/www/{SiteDirectory}
php composer.phar dump-autoload
php artisan migrate --force
php artisan route:clear
php artisan route:cache
php artisan cache:clear
php artisan config:clear
php artisan config:cache
```

## License

MIT. See included `LICENSE` file.

## Contact

For more information or for contributions, contact:

- Rick Sharp (rick@causelabs.com)
