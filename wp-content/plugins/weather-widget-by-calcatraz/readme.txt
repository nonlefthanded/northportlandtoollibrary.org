=== Weather Widget by Calcatraz ===
Contributors: danmossop
Donate link: 
Tags: weather, widget
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 1.3.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple, compact weather widget which displays current conditions and 3-day forecast for the selected location.

== Description ==

The perfect way to show current conditions and a 3-day forcast in your sidebar, for a location of your choice. Great for travel sites, town or city sites, etc. It looks good, takes up little space and is easy to set up.

The widget uses accurate weather data from World Weather Online.

= About the Weather Widget = 

The Weather Widget by Calcatraz is a compact Wordpress weather widget for your sidebar. It has a neat, neutral design which fits in with almost any site. For the location of your choice (city, town, coordinates, ip address, etc) it shows the current weather conditions on the left and a 3-day forcast on the right.

The widget can display temperatures in both celcius and fahrenheit scales. It can detects the user's language and shows the appropriate scale, or use a predefined scale. It also allows the user to switch scales, and remembers their preference.

There are now two styles: the default wide style (260 x 58px), and the new square style (133 x 116px) suitable for narrower sidebars.

== Installation ==

To install the widget on your site, do the following:

1. In your Wordpress Dashboard go to Plugins > Add New
2. Search for "Weather Widget by Calcatraz"
3. Click 'Install Now'
4. Click 'Activate Plugin'
5. Go to Appearance > Widgets
6. Drag the 'Weather Widget by Calcatraz' box into the sidebar area
7. Click on the 'Weather Widget By Calcatraz' to expand the options
8. Fill out the required information (you can the API key for free at http://developer.worldweatheronline.com/)
9. Click 'Save'

That's it, the weather widget should now show up in your sidebar

== Frequently Asked Questions ==

= Can the widget show temperature in Fahrenheit? =

Yes, it can now show both Celcius and Fahrenheit. If the scale option is set to "auto", the widget shows fahrenheit by default to US users (more precisely, those with their browser language set to US English), and Celcius to others. Otherwise, it will use the scale chosen in the widget configuration panel. The user can switch between fahrenheit and celcius, and their preference will be remembered for the duration of their session.

== Screenshots ==

1. Weather Widget by Calcatraz (default size)
2. Weather Widget by Calcatraz widget configuration panel
2. Weather Widget by Calcatraz (new square size)

== Changelog ==

= 1.3.7 =
* Updated version number

= 1.3.6 =
* Minor CSS fixes

= 1.3.5 =
* Now trims keys to prevent api key failure when trailing spaces present
* CSS fixes
* Fixed option to disable / enable credits

= 1.3.4 = 
* Better handling of error reporting when no network connection

= 1.3.3 =
* Added error reporting in widget settings panel
* Can now use both curl and file_get_contents when requesting weather data, so should work in more cases.

= 1.3.2 = 
* Switched from PHP sessions to JS cookies

= 1.3.1 = 
* Fixed bug where if no size specifed (e.g. when upgrading), square size was chosen instead of the default.

= 1.3 = 
* Optimized CSS delivery and selectors for faster loading
* Fixed bug where setting base tag could prevent scale switching from working
* Added new square size
* Added option for picking default scale (fahrenheit or celcius). Auto attempts to show the user the best scale.

= 1.2.2 = 
* Fixed bug where styles not applied

= 1.2.1 = 
* Fixed multi-line weather description formatting
* Fixed bug where scale toggle link displayed over drop-down menus

= 1.2 =
* Fahrenheit support added. Now shows fahrenheit by default to US users, and Celcius to others. The user can switch between fahrenheit and celcius, and their preference will be remembered for the duration of their session.
* Now center aligns in sidebar
* Minor formatting improvements

= 1.1 =
* Minor changes to plugin directory listing

= 1.0 =
* Created weather widget

== Upgrade Notice ==

= 1.3.7 =
Updated version number.

= 1.3.6 = 
Minor bug fixes.

= 1.3.5 =
Minor bug fixes. Enable / disable credits option working again. CSS improvements.

= 1.3.4 = 
Better handling of error reporting when no network connection

= 1.3.3 =
Added error reporting and alternative method of weather data requesting

= 1.3.2 = 
Change to preference storage

= 1.3.1 = 
Minor bug fix

= 1.3 =
Bug fixes, CSS optimization, option to set default scale, and new square size.

= 1.2.2 = 
Fixed bug where styles not applied.

= 1.2.1 = 
Minor bug fixes.

= 1.2 = 
Fahrenheit support added.

= 1.1 = 
Minor changes to documentation. No change to functionality.

= 1.0 =
Initial widget release

