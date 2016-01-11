Laravel 5.2 Boilerplate
=======================

# Introduction
This package provides the basic frame work to quickly start a new Laravel 5.2 project. It also includes several packages and features that
we commonly use. The instructions in this package assume a PHP 7 setup, but can be adjusted for other versions.


# Getting Started
This section contains information regarding setting up your project once you have downloaded this boilerplate. Once you complete these steps,
you can remove this section from the README. Sections below this one should remain in the README.

Once you have downloaded this package, take the following steps:

1. Update the composer.json file to provide the proper package name, description, license, authors, etc.
1. Update the bower.json file to provide proper package name, authors, etc.
1. Replace any instances of "{sitename}" in this README with a site name/url to use when accessing the test site.
1. Update the SITE_URL varilable value in setup-mac.sh to an appropriate URL.
1. Remove this section of the README
1. Continue with the Installation section below.



# Installation

1. Install Dependencies - Part 1
  * Node - [Install it here.](https://nodejs.org/)
  * Virtual Box - [Download it here.](https://www.virtualbox.org/wiki/Downloads)
  * Vagrant [Download it here.](http://www.vagrantup.com/downloads.html)

2. Check out this repository.

3. Open Terminal or a command prompt, and navigate to the root directory of the site.

4. If you have a Mac:
  1. Run `sudo ./setup-mac.sh`
  2. Skip to the **Using the Development Server** section
    *Note: If there's a problem and you need to re-run this script, you MAY need to run these commands:*
    ```
    vagrant destroy (possibly with sudo)
    sudo rm -rf /var/root/VirtualBox\ VMs/{VM_NAME} (Replacing the VM_NAME with the actual VM name)
    ```

5. Install Dependencies - Part 2
  * Bower - `npm install -g bower`
  * Vagrant Box (with PHP 7) `vagrant box add laravel/homestead --box-version 0.4.0`

6. Configure Homestead (Optional, provides a local development environment)
  - Edit the configuration file Homestead.yaml
  - Change the value for folders: -> map to the root directory of this site on your local machine.
  - Record the value under "ip:", and save and close the file.
  - Add the local domain to the hosts file. On Mac (replacing the IP address with that listed in the Homestead config):

    ```
    echo '192.168.10.10    {sitename}.app' | sudo tee -a /private/etc/hosts;dscacheutil -flushcache
    ```


7. Install the packages
  ```
  php composer.phar selfupdate
  php composer.phar install
  npm install
  bower install
  ```

8. Set the configuration file.
The site configuration files are stored in .env. This file is excluded from the git repository, so that the repository does not contain passwords 
or other sensitive information. You can either use a provided .env file, or copy the included .env.example to .env, and update the values.

9. Regenerate the application key.

  Run `php artisan key:generate`

10. Set up the local database, if applicable.
  If you will be using a local development database, run the following commands to set up the tables and test data:
  ```
  php artisan migrate
  php artisan db:seed
  ```
  *Note: Skip this step if you will be connecting to a pre-existing database.*


# Using the Development Server
To start the local development server, just type `vagrant up` into a terminal window located at the site root. Then, you can access http://{sitename}.app.
When developing, use the `gulp watch` command to handle auto-compliation of SCSS, JS, and copying files.

## If Things Are Not Working Right
Try entering these commands into the site root in a Terminal window:
```
php composer.phar install
php composer.phar update
php artisan config:clear
php artisan cache:clear
```

# Gulp Tasks and Source File Locations
Gulp is set up to perform several services, both for development and deployment. It expects certain file types to be in speciic locations. The
following is a breakdown of the functions that Gulp performs:

### SASS
- All site SASS files should be located in /resources/assets/sass directory.
- Gulp compiles SASS to two files:
  - /public/build/css/app.css - All files in the /resources/assets/sass directory
  - /public/build/css/vendor.css - Specific files from the /bower_components directory. Each file must be specifically added in gulpfile.js
- To reference these files in blade layouts, use the "cdn" and "elixir" commands: `<link rel="stylesheet" href="{{ cdn(elixir('css/app.css')) }}">` This provides the correct path and version.
- Compiled CSS files will automatically be versioned.
- You will generally want to include vendor.js first, so that you can override styles as necessary. This provides the correct path and version.
- Source map files will automatically be generated.

### JavaScript
- All site JavaScript files should be located in the /resources/assets/js directory.
- Gulp compiles JavaScript to two files:
  - /public/build/js/app.js - All files in the /resources/assets/js directory
  - /public/build/js/vendor.js - Specific files from the /bower_components directory. Each file must be specifically added in gulpfile.js
- To reference these files in blade layouts, use the "cdn" and "elixir" commands: `<script src="{{ cdn(elixir('js/vendor.js')) }}"></script>`
- Compiled JS files will automatically be versioned.
- You will generally want to include vendor.js first.
- Source map files will automatically be generated.

### Images
- All images should be located in the /resources/assets/images directory.
- Gulp will version these images, and copy them to /public/build/images.

### Fonts
- Font files for Glyphicons and Font Awesome will be copied to /public/buid/fonts.

# Included Packages
The following packages are included with this installation by default:

### JavaScript
- [jQuery](https://jquery.org/)
- [Twitter Bootstrap](http://getbootstrap.com)
- [Bootbox](http://bootboxjs.com/)  - A bootstrap-based dialog helper.
- [Font Awesome](https://fortawesome.github.io/Font-Awesome/) - Because glyphicons are a bit lacking

### Debugbar
The Laravel debugbar is useful in development to show statistics on queries, vews, etc. The bar can be disabled by changing APP_DEBUG to false in .env.

# Added Functionality
The following functionality has been added by default:

### Google Analytics
- Add your GA property ID to the .env file like this: `GOOGLE_ANALYTICS_PROPERTY=UA-XXXXXXXX-XX`
- Analytics code is automatically included in /resources/views/layouts/partials/google-analytics.blade.php
- To add custom dimentions, you can define the values in /app/providers/ComposerServiceProvider.php, then add them to the partial above
- To send analytics events, there is a JS function available: `clSendAnalyticsEvent(category, action, label, value)`
- You can add event listeners to send these functions to /resources/assets/js/widgets/analytcs.js

### Blade Template Commands
The following custom commands have been added to blade templates:
- datetime(\DateTime object): Outputs the date/time like "06/17/2014 17:23"
- isodate(\DateTime object): Outputs the date/time like "2004-02-12T15:19:21+00:00"
- verbosedate(\DateTime object): Outputs the date/time like "Mon, January 7th, 2015"
- verboseshortdate(\DateTime object): Outputs the date/time like "Monday, January 7th"
- @define $variable = "whatever"

### Breadcrumbs
Bootstrap-styled breadcrumb navigation. See /app/Http/breadcrumbs.php for details.

### Favicon
It's recommended to use [Real Favicon Generator](http://realfavicongenerator.net/) to convert a PNG file to favicons. Once that's done, the icon files can be placed in /resources/favicons, and the HTML markup placed into /assets/views/layouts/partials/favicons.blade.php

### JavaScript Functions
- clShowLoading() - Shows a loading overlay. Useful when waiting for an AJAX call to return
- clHideLoading() - Remove the loading overlay.
- clInitFormListeners(container) - Listens for changes of the value of all fields within the container. If the user tries to navigate away from the page without saving changes (via form submission or AJAX), they are shown a confirm navigation message. The container can be a form, or a containing element. This will also enable/disable the form's submit button based on the HTML5 validity of the form elements.
- clResetFieldChangeStatus(field) - For a field whose values are being tracked via the above function, reverts the value and the field status.

### PHP Helper functions
- cdn(filePath) - Prepends a relative filepath with the CDN prefix defined in .env. For example, if .env contains `CDN="cdn.causelabs.com/"`, then `cdn("js/custom.js")` would output: //cdn.causelabs.com/js/custom.js. This allows CDNs to be specified on a per-installation basis. If no CDN is set in .env, the path is set to the site root.
- field($fieldName, $model = null, $prefix = '') - Pre-fills a form input with value from session / model
- options($optionList, $selectedValue = null) - Generates HTML for a set of select field options.
- redirectWithValidation($route, $validator, $params = []) - Produces a Redirect object with the appropriate validation message.

### SASS
- The /resources/assets/sass/lib folder includes some basic variable definitions and mixins.


# Special Classes
- **.cl-js-delete-button-form**: Use this on delete buttons in forms. This will issue a confirmation prompt before proceeding.
- **.cl-js-link-button**: Any buttons with the class "cl-js-link-button" will function as a link. The URL should be contained in data-href.
- **.cl-js-link-button-new**: Same as above, but opens in a new tab.
- **.cl-js-select-on-focus**: Any form field with this class will automatically select all contents on focus or click.
- **.cl-js-ajax-field**: Any field containing this class will automatically be saved via AJAX upon blur, assuming the following prerequisites are met:
    - The CSRF Token must be stored in a variable on the page: `var csrfToken="{!! csrf_token() !!}";`
    - The field must have a data-url attribute containing the URL to which it should post: `data-url="{{ route('admin-workshop-post', ['programId' => $programId, 'id' => $workshop->id]) }}"`
- **.cl-js-delete-button-ajax**: Any button containing this class will automatically delete the record via AJAX after confirming deletion, assuming the following prerequisites are met:
    - The CSRF Token must be stored in a variable on the page: `var csrfToken="{!! csrf_token() !!}";`
    - The field must have a data-url attribute containing the URL to which it should post: `data-url="{{ route('admin-workshop-post', ['programId' => $programId, 'id' => $workshop->id]) }}"`
    - The field must have a data-id attribute containing the record id
    - The field must have a data-description attribute containing the type of record being deleted.
- **.cl-print-only**: The element is only displayed when printing
- **.cl-no-print**: The element is hidden when printing.
- **.cl-js-print-button + .cl-print-button**: The element is styled as a print button (.cl-print-button), and will open the print dialog when clicked (.cl-js-print-button).
- **.cl-js-no-change**: Any field with this CSS class will not trigger a form change.

### Suggested CSS Naming Convention
We are using a few basic namespacing rules to name our CSS class, depending on a number of factors. The main format would look like:
```
.[project_initials]-[js|u|is_...|has_...|c|qa]?-[block-name-or-context]__[child_block]--[modifier]
```
For instance, if we are designing CSS rules for a project named "Foo Project", some CSS classes could be:
- **fp-js-validated-form**: A form that will be validated with JavaScript. Please note that classes with the **js** particle should not be used for styling.
- **fp-u-margin-top--large**: This element will have a margin top applied. Please note the modifier **--large** and the *u* particle that means "utility".
- **fp-is-active**: CSS classes that depend on a particular condition should use the particle **is_whatever-state-or-condition**
- **fp-qa-artificial-hook**: This hook is used solely for QA purposes and does not affect the interface's behavior nor appearance.
- **fp-c-component_name**: This is a standalone component that can be reused all over the application without interferences with other UI elements.
- **fp-feedback-container__comment--small**: Any comment within a feedback container, displayed as small in the current context.

While this naming convention is very opinionated and the benefits / drawbacks of applying to its full extent are arguable, we recommend to 
stick at least to the minimum form `initials-purpose-name`, like **cl-js-print-button** or **cl-qa-submit-button**. This will:

1. Prevent conflicts and collisions with third-party libraries.
1. Explicitly state the purpose of the class: **js** for JavaScript hooks, **qa** for QA hooks, **u** for application-wide utility classes, and so forth. 
1. Isolate components in our own interfaces, thus avoiding collisions (again!).

[More info](http://csswizardry.com/2015/03/more-transparent-ui-code-with-namespaces/)


# Database Seeding
There are classes set up to handle seeding both "real" data and "test" data. Real data is all data that is required for both production and test environments, such as an admin user or a list of categories. Test data would only be used on a staging or development server.
- Generate "real" data in /database/seeds/RealDataSeeder.php.
- Generate "test" data in /database/seeds/TestDataSeeder.php. Do not duplciate real data in this class, as both classes are run for test environments
To seed the data:
- Real only: `php artisan db:seed --class=RealDataSeeder`
- Real and Test: `php artisan db:seed `


# Deploying to Stage/Production

It is recommended that the deployment script include the following commands:
### Pre-Deployment:
```
php composer.phar selfupdate
php composer.phar install
php composer.phar update
npm install --production
bower install
bower prune
node_modules/gulp/bin/gulp.js --production
chmod -R g+w bootstrap
chmod -R g+w storage
```

### Deployment
```
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

### Post-Deployment
```
cd /var/www/{SiteDirectory}
php composer.phar dump-autoload
php artisan migrate
php artisan route:clear
php artisan route:cache
php artisan cache:clear
php artisan config:clear
php artisan config:cache
```