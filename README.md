# Layout Builder Additions

Provides additions for the core Layout Builder module that improves the UI and
experience.

## Installation

There are no special installation instructions. Follow Drupal's standard
instructions for [installing modules](https://www.drupal.org/docs/extending-drupal/installing-modules).

### Composer

If your site is [managed via Composer](https://www.drupal.org/node/2718229), use
Composer to download the module:

   ```sh
   composer require "drupal/layout_builder_additions 1.0.x-dev"
   ```

Use ```composer update drupal/layout_builder_additions --with-dependencies```
to update to a new release.

## Features

The primary features include:

* Combine the "Configure block" and "Block description" in the UI for inline
blocks.

  When creating an inline block, the "Configure block" heading and the "Block
  description" take up quite a bit of space. It would be better for the block
  description to be combined so that it reads "Configure block: ." For a block
  type called Accordion, for example, the heading would read "Configure block:
  Accordion."

  This module will work with both the core Layout Builder interface, as well as
  with the recommended Layout Builder Modal module (see below).

## Recommended Modules

* [Layout Builder Modal](https://www.drupal.org/project/layout_builder_modal)
* [Layout Builder Restrictions](https://www.drupal.org/project/layout_builder_restrictions)

## Credits

Currently maintained by [Rich Lawson](https://www.drupal.org/u/rklawson) and
[Jason Thompson](https://www.drupal.org/u/galactus86) and sponsored by
[Mediacurrent](https://www.mediacurrent.com).

## Contact Information

The best way to contact the authors is to submit an issue, be it a support
request, a feature request or a bug report, in the project
[issue queue](https://www.drupal.org/project/issues/layout_builder_additions).
