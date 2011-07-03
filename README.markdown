Markup Validator Bundle
=======================

Bundle that provides markup validation functionality to your Symfony2 based
projects.

This bundle is in early development state, so any contribution is welcome! ;)

Define validators
-----------------

The first step, when the bundle is installed in your project, is to define
validators in your application configuration:

    # app/config/config.yml
    knp_markup_validator:
        default_validator:  default
        validators:
            default:
                processor:  tidy

It tells to the markup validator extension to create a validator service named
`markup_validator.default_validator` using the tidy processor.

The `default_validator` tells the extension to create the `markup_validator`
service which is an alias for the `markup_validator.default_validator` one.

You can define as many validators as you want.

Processors
----------

Processors are used by the validator to validate the markup. They are
responsible to return an array of warning and error messages.

The bundle provides two processors:

 * *w3c* which uses the validator.w3.org's api
 * *tidy* which uses the tidy binary

If you want to define your own validator, you simply need to create a service
implementing the `Knp\MarkupValidatorBundle\Validation\ProcessorInterface`
with the `markup_validator.processor` tag and its name as alias tag attribute.
The extension will create a service for each validator named as follow:
`markup_validator.{{ alias }}_processor`.
