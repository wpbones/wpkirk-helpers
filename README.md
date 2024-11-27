# WP Kirk Helpers

<div align="center">

[![Latest Stable Version](https://poser.pugx.org/wpbones/wpkirk-helpers/v/stable?style=for-the-badge)](https://packagist.org/packages/wpbones/wpkirk-helpers) &nbsp;
[![Latest Unstable Version](https://poser.pugx.org/wpbones/wpkirk-helpers/v/unstable?style=for-the-badge)](https://packagist.org/packages/wpbones/wpkirk-helpers) &nbsp;
[![Total Downloads](https://poser.pugx.org/wpbones/wpkirk-helpers/downloads?style=for-the-badge)](https://packagist.org/packages/wpbones/wpkirk-helpers) &nbsp;
[![License](https://poser.pugx.org/wpbones/wpkirk-helpers/license?style=for-the-badge)](https://packagist.org/packages/wpbones/wpkirk-helpers) &nbsp;
[![Monthly Downloads](https://poser.pugx.org/wpbones/wpkirk-helpers/d/monthly?style=for-the-badge)](https://packagist.org/packages/wpbones/wpkirk-helpers)


</div>

WP Kirk Helpers is a (internal) package that provides a set of functions to help you to develop a WP bones Boilerplate plugin.

## Installation

You can install third party packages by using:

```sh
php bones require wpbones/helpers
```

I advise to use this command instead of `composer require` because doing this an automatic renaming will done.

You can use composer to install this package:

```sh
composer require wpbones/helpers
```

You may also to add `"wpbones/wpkirk-helpers": "~1.0"` in the `composer.json` file of your plugin:

```json
  "require": {
    "php": ">=7.4.0",
    "wpbones/wpbones": "~1.7",
    "wpbones/wpkirk-helpers": "~1.0"
  },
```

and run

```sh
composer install
```

## Automatic Release Creation

This repository includes a GitHub Actions workflow that automates the creation of a GitHub release and updates the `CHANGELOG.md` file whenever a new tag is pushed to the repository.

### Workflow File

The workflow file is located at `.github/workflows/create-release.yml`. It performs the following actions:

- Triggers on the creation of a new tag.
- Fetches the latest commits since the previous tag.
- Creates a GitHub release with the tag name as the title and the latest commits as the content.
- Prepends the release note to the beginning of the `CHANGELOG.md` file.
- Commits and pushes the updated `CHANGELOG.md` file to the repository.

By using this workflow, you can ensure that your release notes are always up-to-date and automatically generated based on the latest commits.

