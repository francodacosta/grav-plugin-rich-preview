# Rich Preview Plugin

The **Rich Preview** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). Sets thumbnail and description so you can control how a link to your website looks like when shared, for example in whatsApp

## Installation

Installing the Rich Preview plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install rich-preview

This will install the Rich Preview plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/rich-preview`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `rich-preview`. You can find these files on [GitHub](https://github.com/francodacosta/grav-plugin-rich-preview) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/rich-preview

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/rich-preview/rich-preview.yaml` to `user/config/plugins/rich-preview.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named rich-preview.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

You will have two new fields on the *options* tab of *Edit/Add Page* of *Grav Admin Plugin*.

Those will allow you to set a description and thumbnail for the page.

If not description is provided, the global description is used if none is set we try to compute one from the page content.

By default the plugin will use the first image in page media for thumbnail.

To change it you can either provide an URL or an index number of the page media you want to use (start counting at 0)
