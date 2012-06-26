Settings Manager for eZ Publish
=======

What is the Settings Manager for eZ Publish
----------------------------------------------------------
The Settings Manager allows you to get a quick overview of what ini settings take place, and how they are overwritten.

Although the classic settings configuration screen shows you this information already, it is yet hard to find the information you need, especially when having multiple siteaccesses.

The Settings Manager allows you to comfortably compare the values set in the individual siteaccess settings, default and override settings, provided by a filterable / searchable grid view powered by ExtJS.

Installation
----------------------------------------------------------
To install the extension, follow these simple steps:

1. Install hwsettingsmanager
You can either download this extension from here, or clone the repository into you extension directory. In any case, make sure it is placed in {ezpublishroot}/extension/hwsettingsmanager.

2. Edit your settings/override/site.ini.append.php

        [ExtensionSettings]
        ActiveExtensions[]=hwsettingsmanager

3. Eventually, clear you cache

4. In the admin area, navigate to the new top- menu entry "Settings Manager"

### Feedback and Feature Requests
We're happy to receive your feedback at technik[at]holzweg.com

Please feel to contribute by contacting us or simply sending us a pull request.

### Initially brought to you by Holzweg e-commerce Solutions, with love. ###

Gracias!