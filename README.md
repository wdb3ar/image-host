# image-host
Simple image storage with tags and API

## Requirements
- Web server Apache
- PHP 5.6 / 7
- MySQL / MariaDB

## Technologies used
- Twitter Bootstrap
- jQuery
- Magnific Popup
- Selectize.js
- esimakin/twbs-pagination

## Installation
1. Configure the Web server so that the document root is the public directory.
2. Create a database and import the tables from the sql dump file into it.
3. Edit the config.php file.

## How to use the API
- The API is available at example.com/api
- Available Get parameters: tag, page, pageSize.
- If the tag is not specified in the query parameters, it returns all images.
- pageSize - number of items displayed on the page. If not specified is taken from the configuration file.
