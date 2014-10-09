# Dev7studios WordPress Starter Theme

A minimal WordPress starter theme based on HTML5 Boilerplate & Bootstrap. A lot of WordPress starter
themes already have a lot of stuff included that you might not want or need. The idea begind the
Dev7studios Starter Theme is that it gives you a very minimal setup with just the right amount of HMTL.

* Uses very minimal [Bootstrap](http://getbootstrap.com) compatible HTML5
* Use [Bower](http://bower.io) for install front end dependencies
* Uses [Grunt](http://gruntjs.com) for a build script
* Setup to work with [SASS](http://sass-lang.com)

## Install

1. Clone or download the theme
2. Install Bower and Grunt-CLI if you don't have them installed `npm install -g grunt-cli bower`
3. Run `npm install` to install Grunt dependencies
4. Run `bower install` to install front end dependencies
5. Run `grunt` to build the assets

## Usage

* All CSS/JS/SASS is kept the the relevant `assets` subdirectory
* Bower dependencies will get installed to `assets/vendor`
* Don't use the root `style.css` for CSS as it doesn't get enqueued. Use `assets/sass/style.scss` instead
* Template parts (such as header and footer) are kept in the `parts` folder
* Page templates go in the `templates` folder

## Credits

Dev7studios WordPress Starter Theme was created by [Gilbert Pellegrom](http://gilbert.pellegrom.me) from
[Dev7studios](http://dev7studios.com). Released under the MIT license.