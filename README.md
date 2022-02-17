CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers
 * Contact Information


INTRODUCTION
------------

The Layout Builder Additions module provides additions for the core Layout 
Builder module that improves the UI and experience. This module is currently 
available for Drupal 8.7.7+ and 9.x.x.

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

* Adds a "Layout" entity operation.

  Adds a "Layout" entity operation for content types that allow overridable
  layouts. This is useful on the content admin screen to save extra steps/clicks
  for editing layouts.


REQUIREMENTS
------------

This module requires the Layout Builder module in Drupal core.


INSTALLATION
------------

Install the Layout Builder Additions module as you would normally install a 
contributed Drupal module. Follow Drupal's standard instructions for 
[installing modules](https://www.drupal.org/docs/extending-drupal/installing-modules).

If your site is [managed via Composer](https://www.drupal.org/node/2718229), use
Composer to download the module:

   ```sh
   composer drupal/layout_builder_additions
   ```

Use ```composer update drupal/layout_builder_additions --with-dependencies```
to update to a new release.


CONFIGURATION
-------------

The module has no menu or modifiable settings. There is no configuration. When
enabled, the module will prevent the links from appearing. To get the links
back, disable the module and clear caches.


RECOMMENDED MODULES
-------------------

* [Layout Builder Modal](https://www.drupal.org/project/layout_builder_modal)
* [Layout Builder Restrictions](https://www.drupal.org/project/layout_builder_restrictions)


MAINTAINERS
-----------

* [Rich Lawson](https://www.drupal.org/u/rklawson)
* [Jason Thompson](https://www.drupal.org/u/galactus86)

Supporting organization:

* [Mediacurrent](https://www.mediacurrent.com)


CONTACT INFORMATION
-------------------

The best way to contact the maintainers is to submit an issue, be it a support
request, a feature request or a bug report, in the project
[issue queue](https://www.drupal.org/project/issues/layout_builder_additions).
