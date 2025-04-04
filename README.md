# OWC PDC Leges

## Getting started

### Installation

#### For users

1. Download the latest release from [the releases page](https://github.com/OpenWebconcept/plugin-pdc-leges/releases)
2. Unzip and move all files to the `/wp-content/plugins/plugin-pdc-leges` directory.
3. Log into the WordPress admin and activate the 'PDC Leges' plugin through the 'plugins' menu

#### For developers

To contribute to this project, no dependencies are required. However, you will need to download [Composer](https://getcomposer.org/) to run tests or create an optimized build of the plugin.

1. Clone this repository to your machine and/or WordPress installation
2. Use Composer (`composer install`) to install the dev dependencies
3. Use NPM (`npm install`) to install de dev dependencies (node version defined in [.nvmrc](.nvmrc))

To create an optimized and zipped build, run the `composer run package` command. This requires `Composer`, `rsync` and `zip` to run.

##### Husky

We use [Husky](https://github.com/typicode/husky) to manage our Git hooks. Husky helps to ensure that code quality is maintained by running scripts before certain Git actions, such as commits.

After you run `npm install`, Husky is automatically configured and ready to use. Before each commit, Husky will automatically run the `composer run format` command to ensure that your code is properly formatted. This helps maintain consistent code style and quality across the project.

To summarize:

1. **Automatic Setup:** Husky is set up automatically when you run `npm install`.
2. **Pre-Commit Hook:** Before committing, Husky runs `composer run format` to format the code.
3. **Ensures Code Quality:** This pre-commit hook helps maintain consistent code style and quality.

By using Husky, we enforce code formatting rules, making collaboration easier and code more maintainable.

### Commands

Since version 2.0.0, the commands have been replaced by WP Cron Events. This change requires less configuration on your server by eliminating the need to add server cron jobs. Just activate the plugin and you're all set.
Remember to remove any previously configured cron jobs from your web server, as they have been deprecated since version 2.0.0.

## REST API Endpoints

This plugin registers several REST API endpoints to retrieve information about "leges". Below is a description of the available endpoints and their corresponding parameters.

### Endpoint: `/wp-json/owc/pdc/v1/leges`

- **Method:** GET
- **Description:** Retrieves a list of "leges" posts.
- **Parameters:**
  - `limit` (integer): The number of posts per page. Default is 10. Minimum is -1. Maximum is 100.
  - `page` (integer): The current page number. Default is 1.
  - `meta_key` (string, optional): The meta key to filter by.
  - `meta_value` (string, optional): The meta value to filter by. Multiple values are supported, use a comma seperated string when you want to filter on multiple values.

**Example Request:**

```sh
GET /wp-json/owc/pdc/v1/leges?limit=5&page=2&meta_key=_pdc-lege-price&meta_value=7.63
```

#### Endpoint: `/wp-json/owc/pdc/v1/leges/(?P<id>\d+)`

- **Method:** GET
- **Description:** Retrieves a specific "lege" post by its ID.
- **Parameters:**
  - `id` (integer): The ID of the post.

```sh
GET /wp-json/owc/pdc/v1/leges/123
```

#### Endpoint: `/wp-json/owc/pdc/v1/leges/(?P<slug>[\w-]+)`

- **Method:** GET
- **Description:** Retrieves a specific "lege" post by its slug.
- **Parameters:**
  - `slug` (string): The slug of the post.

```sh
GET /wp-json/owc/pdc/v1/leges/sample-slug
```

### WP Cron Events

Lege prices are automatically updated by a scheduled event registered by this plugin. Currently, this plugin registers one event, but more may be added in the future.

#### Event 'owc_pdc_leges_update_cron'

A lege has 3 settings:

- Current lege price
- New lege price
- Date active new lege price

The event queries all leges that have valid values in the "New lege price" and "Date active new lege price" fields. If the date in the "Date active new lege price" field has expired, the "Current lege price" field will be updated with the new price. If the update succeeds, the "New lege price" and "Date active new lege price" fields will be cleared.

### Error Logging

Errors will be written to the WordPress debug.log file. To enable this, the WP_DEBUG constant needs to be defined as true. Currently, errors occurring while executing WP Cron Events are logged. In the future, logging will be expanded to cover the entire plugin at appropriate locations.

### Filters & Actions

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

#### Action for changing main Plugin object

```php
'owc/pdc-leges/plugin'
```

Via the plugin object the following config settings can be adjusted

- metaboxes
- rest_api_fields

##### Filter the format of the shortcode output

```php
// Default format: '<span>&euro; %s</span>'
add_filter('owc/pdc/leges/shortcode/format', function(string $format){
 return str_replace('span', 'b', $format); // Returns '<b>&euro; %s</b>'
}, 10, 1);
```

##### Filter the output of the shortcode output

```php
// $output: '<b>&euro; 10,00</b>'
add_filter('owc/pdc/leges/shortcode/after-format', function ($output){
 return str_replace('b', 'span', $output); // Returns: '<span>&euro; 10,00</span>'
}, 10, 1);
```

##### Add custom CMB2 metaboxes

```php
add_filter('owc/pdc/leges/metabox/extension-fields/add', function($cmb, $prefix){
 $cmb->add_field([
  'name' => 'Custom field name',
  'desc' => 'Custom field description',
  'id' => sprintf('%s-custom-field-id', $prefix),
  'type' => 'text',
 ]);
}, 10, 2);
```

##### Add meta values of custom CMB2 metaboxes to the output of the REST API

```php
add_filter('owc/pdc/leges/rest-api/output/extension-fields/add', function(array $output, WP_Post $post){
 return array_merge($output, [
  'custom-field-id' => get_post_meta($post->ID, '_pdc-lege-custom-field-id', true) ?: null
 ]);
}, 10, 2);
```

##### Filter allowed meta keys used for filtering the leges endpoint

```php
  add_filter('owc/pdc/leges/rest-api/args/allowed-meta-keys', function ($allowed){
   return array_merge($allowed, ['_pdc-lege-custom-field-id']);
 });
```

### Translations

If you want to use your own set of labels/names/descriptions and so on you can do so.
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/pdc-leges/languages/pdc-leges.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the 'Loco Translate' plugin.
<https://wordpress.org/plugins/loco-translate/>

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advise you to read the Beginner's guide page on their website: <https://localise.biz/wordpress/plugin/beginners>
or start at the homepage: <https://localise.biz/wordpress/plugin>

### Running tests

To run the Unit tests go to a command-line.

```bash
cd /path/to/wordpress/htdocs/wp-content/plugins/pdc-leges/
composer install
phpunit
```

For code coverage report, generate report with command line command and view results with browser.

```bash
phpunit --coverage-html ./tests/coverage
```

### Contribution guidelines

#### Writing tests

Have a look at the code coverage reports to see where more coverage can be obtained.
Write tests
Create a Pull request to the OWC repository

### Who do I talk to?

If you have questions about or suggestions for this plugin, please contact <a href="mailto:hpeters@Buren.nl">Holger Peters</a> from Gemeente Buren.
