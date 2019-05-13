# TYPO3 users

Find on 

* TER: https://extensions.typo3.org/extension/users/
* Packagist: https://packagist.org/packages/saschaende/users
    * ```composer require saschaende/users```

Users brings you all essential plugins (login, logout, register, forgot password, banlist for spam hosts...) to build a community with your TYPO3 system. Based on easy to customize fluid templates.

## Features of the "Users" Extension

* User friendly, no complicated typoscript: You can configure all settings via the plugins
* FLUID templates, based on bootstrap 4, easy to customize. Even for emails.
* Security:
    * According to https://typo3.org/security/advisory/typo3-psa-2019-002/, Username and Email Address Enumeration is not possible in all plugins. If a user registers with an email, that exists, the email adress automatically gets a remember mail with an password reset link. (Thanks to vasyl from http://typo3.net.ua/)
    * A list of 2160 disposable email address providers is included and can be imported with one click.
* Plugin: Login
    * Login with username AND/OR email
    * Configurable input fields
    * Save login count for every user
* Plugin: Logout (so the user can logout just with a click on this page)
* Plugin: Forgot password
* Plugin: Change email (for registered users, with confirmation of the new adress via email)
* Plugin: Change password (for registered users)
* Plugin: Register
    * Set user groups that will be automatically assigned
    * Protect your system from disposable email addresses: Banlist for not allowed domains/hosts (spamschlucker ...)
* Module: Admin tool for importing newest spam host list (will be regularily updated)
* Multi Site:
    * Saves root page uid for every registered user
    * Saves language setting of every user
* Language files delivered: German / English

## Planned / ToDo

* Plugin: Change profile data
* Plugin: Change email (with verficiation of new email)
* Command: Scheduler to remove not activated users
* Module/Command: Newsletter / bulk sending with scheduler
* Signals
* Additional options for user names:
    * Setting A: Show input field and automatically generate a user name if the user does not enter one.
    * Setting B: Hide input field and always automatically generate a user name.
    * Setting C: User name is not generated automatically, input is mandatory.

## Installation

### Preparation: Include static TypoScript
The extension ships some TypoScript code which needs to be included.

* Switch to the root page of your site.
* Switch to the Template module and select Info/Modify.
* Press the link Edit the whole template record and switch to the tab Includes.
* Select "Users (users)" at the field Include static (from extensions)
* If you have multiple websites configured, repeat the steps above for every website

### Use the plugins

* Go to any page
* Click on "add content element"
* Switch to the tab "Users"
* Choose a plugin (login, register, ...) and insert it
* Edit the plugin (pencil)
* Edit the settings in the "plugin" tab (see the screenshots here in the documentation)
* Thats it and that's how every "users" plugin works
* No further typoscript

## Important notes

* Very important: Do NOT activate "allow login with email adress" if your system allows usernames with email adresses. The register plugin of this users extension will not allow email adresses as usernames.
* To display the icons in the forms, you need "fontawesome" - get it here: https://fontawesome.com/start
* If you dont want fontawesome, you can configure the icon classes in TYPOSCRIPT
## Screenshots Backend PLugins

## Other credits

* UK Translation by TYPO3.net.ua

### Plugin: Login

General settings
![plugin](Documentation/login.jpg)

Links
![plugin](Documentation/login2.jpg)

### Plugin: Logout

* Add a page "Logout"
* Put the plugin "logout" on the "Logout" page
* Configure the logout plugin (where will the user be redirected after logout?)

Tipp: If you want to add a logout button on your page, just redirect directly to your "Logout" page. You can also make your "Logout" page invisible.

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

## Demos

### Login

![plugin](Documentation/demo_login.JPG)

### Forgot password

![plugin](Documentation/demo_forgot.JPG)

### Register

![plugin](Documentation/demo_register.JPG)

### Admin

Admin Module
![plugin](Documentation/demo_admin.JPG)

Banned Hosts
![plugin](Documentation/demo_hosts.JPG)