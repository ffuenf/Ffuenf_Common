# CHANGELOG for Ffuenf_Common

This file is used to list changes made in each version of Ffuenf_Common.

## 1.2.3 (June 28, 2016)

* add html2plain helper
* add VersionEye dependency info

## 1.2.2 (May 24, 2016)

* fix proper handling of dates/times in profile logging
* fix date/time display in grid and detail view

## 1.2.1 (April 1, 2016)

* fix static checkExtensionHelperMethod

## 1.2.0 (March 19, 2016)

* add details to system logging (full content)
* format log-types in system.log with its own renderer (based on Zend_Log)
* add dependent logging setting in extensions (depends on isLoggingActive() in extensions helper)
* fix php strict notices
* fix log download functionality
* remove event observers from system information
* fix cron information to list all cronjobs by ffuenf
* add origin in system logging to get originating classes
* add system information about installed magento patches

## 1.1.1 (March 12, 2016)

* update travis build-matrix
* [docs] copyright notice

## 1.1.0 (December 15, 2015)

* add system information panel
* add advanced centralized logging panel and logic
* add profile logging
* phpcs / code-style fixes
* remove codeclimate (use scrutinizer-ci instead)

## 1.0.1 (November 22, 2015)

* add uninstallation via [Ffuenf_MageTrashApp](https://github.com/ffuenf/Ffuenf_MageTrashApp)

## 1.0.0 (November 15, 2015)

* fork of Fooman_Common