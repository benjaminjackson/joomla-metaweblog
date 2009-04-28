# Joomla Metaweblog Plugin

## About

The [original version](http://joomlacode.org/gf/project/metaweblogapi/frs/) of this plugin was written by Justo for the Google Highly Open Participation contest.

This version has been modified by Brainjuice to work with [Blogo, its weblog editor for the Mac](http://drinkbrainjuice.com/blogo). It should also work with other third-party clients like Flock and Windows Live Writer.

## Configuration:

**In order to use a remote editor with Joomla, your blog must have the MetaWeblog plugin installed and Web Services enabled:**

1. Login to your blog as an administrator
2. Go to the Site menu and choose "Global Configuration" 
3. Open subsection "System" and change "Enable Web Services" to "Yes"
4. Click "Apply" at the top of the page
5. Go to the Extensions menu and choose "Install/Uninstall"
6. Download the following plugins and upload the ZIP files to Joomla under "Upload Package File":

    * MetaWeblog plugin: http://github.com/benjaminjackson/joomla-metaweblog/zipball/master
    * RSD plugin: http://joomlacode.org/gf/download/frsrelease/7080/23993/rsd.zip
  
  _Note: If you have installed the plugin previously, uninstall it in the "Plugins" subsection by checking it and clicking "Uninstall" at the top of the page._

7. Go to the Extensions menu and choose "Plugin Manager"
8. Enable the plugins ("System - Real Simple Discovery (RSD)" and "XMLRPC - metaWeblog API")
9. Make sure that the media folder in your Joomla installation has write permissions

**In the blog editor:**

* Create a new account
* Type the url of your homepage and your user and password
* If the auto configuration doesn't work:
    * Select MetaWeblog API in blog type
    * Enter http://yoursite.com/xmlrpc/index.php as the XMLRPC gateway (sometimes called API link)
