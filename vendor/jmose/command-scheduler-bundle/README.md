CommandSchedulerBundle
======================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8d984140-0e19-4c4f-8b05-605025eebeb5/mini.png)](https://insight.sensiolabs.com/projects/8d984140-0e19-4c4f-8b05-605025eebeb5)
[![Build Status](https://travis-ci.org/J-Mose/CommandSchedulerBundle.svg)](https://travis-ci.org/J-Mose/CommandSchedulerBundle)
[![Coverage Status](https://coveralls.io/repos/J-Mose/CommandSchedulerBundle/badge.svg)](https://coveralls.io/r/J-Mose/CommandSchedulerBundle)
[![Latest Stable Version](https://poser.pugx.org/jmose/command-scheduler-bundle/v/stable)](https://packagist.org/packages/jmose/command-scheduler-bundle)

This bundle will allow you to easily manage scheduling for Symfony's console commands (native or not) with cron expression.

## Versions & Dependencies

The following table shows the compatibilities of different versions of the bundle :

| Version                                                                                 | Symfony     | PHP    |
| --------------------------------------------------------------------------------------- |  ---------- | ------ |
| [2.x](https://github.com/J-Mose/CommandSchedulerBundle/tree/master)                     | ^3.4\|^4    | >=5.6  |
| [1.2.x](https://github.com/J-Mose/CommandSchedulerBundle/tree/1.2) (unmaintained)       | ^2.8\|^3.0  | >=5.5  |
| [1.1.x](https://github.com/J-Mose/CommandSchedulerBundle/tree/1.1) (unmaintained)       | ^2.3        | >=5.3  |

When using Symfony Flex there is an [installation recipe](https://github.com/symfony/recipes-contrib/tree/master/jmose/command-scheduler-bundle/2.0).  
To use it, you have to enable contrib recipes on your project : `composer config extra.symfony.allow-contrib true`

## Features

- An admin interface to add, edit, enable/disable or delete scheduled commands.
- For each command, you define : 
  - name
  - symfony console command (choice based on native `list` command)
  - cron expression (see [Cron format](http://en.wikipedia.org/wiki/Cron#Format) for informations)
  - output file (for `$output->write`)
  - priority
- A new console command `scheduler:execute [--dump] [--no-output]` which will be the single entry point to all commands
- Management of queuing and prioritization between tasks 
- Locking system, to stop scheduling a command that has returned an error
- Monitoring with timeout or failed commands (Json URL and command with mailing)
- Translated in french, english, german and spanish
- An [EasyAdmin](https://github.com/EasyCorp/EasyAdminBundle) configuration template available [here](Resources/doc/index.md#6---easyadmin-integration)

## Screenshots
![list](Resources/doc/images/scheduled-list.png)

![new](Resources/doc/images/new-schedule.png)

![new2](Resources/doc/images/command-list.png)

## Documentation

See the [documentation here](Resources/doc/index.md).

## License

This bundle is under the MIT license. See the [complete license](Resources/meta/LICENCE) for info.
