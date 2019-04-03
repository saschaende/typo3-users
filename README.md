# TYPO3 users

Users will bring you all essential plugins to build a community with your TYPO3 system. Based on easy to customize fluid templates. The extension will be available for TYPO3 9 as soon as the development is finished.

* Based on bootstrap 4
* Easy to customize (Fluid)
* Easy to install (just put the plugins on the pages and configure directly in the plugin)
* No typoscript configuation needed

## Why only for TYPO3 8?

I develop this extension in an 8 environment on https://filmmusic.io and will make it compatible to 9 after that.

## READY TO USE

* Login
* Logout
* Forgot password
* Register

## In development

* Change password

## Planned / ToDo

* Change profile data
* Change email (with verficiation of new email)
* Scheduler to remove not activated users
* Newsletter with scheduler

## Demos

### Login
* DE https://filmmusic.io/de/login/
* EN: https://filmmusic.io/login/

### Forgot password
* DE https://filmmusic.io/de/login/passwort-vergessen/
* EN: https://filmmusic.io/login/forgot-password/

### Register
* DE: https://filmmusic.io/de/registrieren/
* EN: https://filmmusic.io/register/

## Installation

### Preparation: Include static TypoScript
The extension ships some TypoScript code which needs to be included.

* Switch to the root page of your site.
* Switch to the Template module and select Info/Modify.
* Press the link Edit the whole template record and switch to the tab Includes.
* Select "Users (users)" at the field Include static (from extensions)

### Use the plugins

* Go to any page
* Click on "add content element"
* Switch to the tab "Users"
* Chooke a plugin and insert it
* Edit the plugin
* Edit the settings in the "plugin" tab
* Thats it and that how every users plugin works
* No further typoscript

## Important notes

* Very important: Do NOT activate "allow login with email adress" if your system allows usernames with email adresses. The register plugin of this users extension will not allow email adresses as usernames.

## Screenshots

### Plugin: Login

General settings
![plugin](Documentation/login.jpg)

Links
![plugin](Documentation/login2.jpg)

### Plugin: Logout

General settings
![plugin](Documentation/logout.jpg)

### Plugin: Forgot password

General settings
![plugin](Documentation/forgotpass1.jpg)

Email settings
![plugin](Documentation/forgotpass2.jpg)

### Plugin: Register

General settings
![plugin](Documentation/register1.JPG)
![plugin](Documentation/register2.JPG)

Field settings
![plugin](Documentation/register3.JPG)
![plugin](Documentation/register4.JPG)

Email settings
![plugin](Documentation/register5.JPG)