Raygun4Wordpress
==========

[Raygun.io](http://raygun.io) provider plugin for Wordpress

This provider is a Wordpress plugin that allows you to easily send errors and exceptions from your Wordpress site to the Raygun.io error reporting service.
It features an admin panel for easy configuration. It uses the lower-level Raygun4php provider. 

## Installation

Firstly, ensure that the **curl** extension is installed and enabled in your server's php.ini file. If you are using a *nix system, the package php5-curl may contain the required dependencies.

### Manually with Git

Clone this repository into your Wordpress installation's /plugins folder - for instance at /wordpress/wp-content/plugins. Use the --recursive flag to also pull down the Raygun4php dependency:

```
git clone --recursive https://github.com/MindscapeHQ/raygun4php.git
```

### From Wordpress Plugin Directory

Coming soon

## Usage

In your browser navigate to your Wordpress admin panel, click on Plugins, and 'Activate' Raygun4Wordpress. Click on the new entry that appears to the left.

Copy your application's API key from the Raygun.io dashboard, and place it in the appropriate field. Set Error Reporting Status to 'Enabled', hit Submit, and you're done!