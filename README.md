# Recipes App

[Live demo](https://recipes.jacobhall.net)

My partner and I got tired of keeping track of recipe links and trying to remember our modifications each time we wanted to cook a meal.
This is a simple website I cooked up over a weekend to house the recipes we love to cook together.

This website purposefully does not include [Recipe Schema](https://developers.google.com/search/docs/advanced/structured-data/recipe) or SEO.
Go out and support the wonderful people who publish recipes as a source of income.
This project is for saving and indexing adapted and family recipes for personal use.

## Installation

1. Clone this repository into a web server with HTTPS enabled
2. Install PHP ~7.4 with the JSON and SQLite3 extensions, and SQLite3 with the [JSON1 extension](https://www.sqlite.org/json1.html).
2. Download [Parsedown](https://github.com/erusev/parsedown/releases) and copy `Parsedown.php` into this directory
3. Download [Pico.css](https://github.com/picocss/pico/releases) and copy `css/pico.classless.min.css` into this directory
4. Copy example-creds.php to real-creds.php, and edit it to include your username and password of choice

## Roadmap

For now this app more than meets my needs, but here are some long-term goals:
- support groupings of ingredients for different elements of a meal
- legitimize the PHP side of things with a framework like Laravel
- add PWA features like service workers to allow app to function offline

## Contributing

You are welcome to contact me, create an issue, or submit a pull request.
I will happily work with anyone to improve this project.

## Thanks
Pan icon is [from SVG Repo](https://www.svgrepo.com/svg/265481/pan).
It is in the [public domain](https://creativecommons.org/share-your-work/public-domain/cc0/), and does not require attribution to use.

Thanks to [Maskable.app](https://maskable.app/) for making icon generation easy!

## License

This project is licensed using GPLv3.
Please see the LICENSE file for more information.
