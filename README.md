# mvf
PHP Validation Library


## Code Quality

The majority of the methods in the package have been unit tested. The unit tests are run both in PHP and HHVM.

The only methods that have not been fully tested are the Helpers, which are mostly simple shorthands for SQL strings.

We test our package locally and remotely with Travis-CI:

[![Build Status](https://travis-ci.org/FoolCode/SphinxQL-Query-Builder.png)](https://travis-ci.org/FoolCode/SphinxQL-Query-Builder)

## How to Contribute

### Pull Requests

1. Fork the SphinxQL Query Builder repository
2. Create a new branch for each feature or improvement
3. Submit a pull request from each branch to the **master** branch

It is very important to separate new features or improvements into separate feature branches, and to send a pull
request for each branch. This allows me to review and pull in new features or improvements individually.

### Style Guide

All pull requests must adhere to the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) standard.

### Unit Testing

All pull requests must be accompanied by passing unit tests and complete code coverage. The SphinxQL Query Builder uses
`phpunit` for testing.

[Learn about PHPUnit](https://github.com/sebastianbergmann/phpunit/)
