=== [TiSiE] RelativeTime ===
Contributors: tisie
Donate link: 
Tags: template-tags, date, time, post, comments, relative
Requires at least: 2.9
Tested up to: 3.1
Stable tag: 0.2

Adds template tags to your wordpress blog to let you show dates and times in a relative way.   

== Description ==

Adds template tags to your wordpress blog that lets you show relative time strings, 
such as "3 months and 2 days" or "4 months, 10 hours and 2 minutes".

Unlike other algorithms used to calculate relative time, this plugin uses an algorithm based
on written substraction and therefore the calculated relative time is synchronous to the gregorian 
calendar system.   
Example: Let's assume todays date were "__2010-03-01__" and the post date were "__2010-02-01__". The 
result of this plugin's calculation would be "__1 month__". 
 
Currently only englisch and german localizations are available. But maybe you can help me 
translate to other languages. I will gladly add them. (see "FAQ" for more details)

The following template tags are available

- the_date_relative / get_the_date_relative
- the_modified_date_relative / get_the_modified_date_relative
- comment_date_relative / get_comment_date_relative

Additionally there are two filter hooks available

- tirt_get_string
- tirt_get_array

Since 0.3, there is a shortcode tag available, which lets you display relative time strings in 
posts and pages

- [reltime]

Please see the "[Other Notes](http://wordpress.org/extend/plugins/tisie-relativetime/other_notes/)" 
tab for more information about the usage.

Du kannst auch [auf deutsch](http://tisie.de/plugins/tisie-relativetime "Plugin-Seite in meinem Weblog") lesen.

= This is a BETA release =
So, if something is not working as expected or you get others errors or you encounter incompatibilities to other
plugins - please __report__ them to me so I can fix and improve my plugin.

== Installation ==

1. Upload the .zip file to your plugins folder and unzip
2. Activate the plugin through the plugin management admin interface
3. Add the template tags to your theme files (see "Usage")
4.  (Optional) Uses the filter hooks to modify the output without touching the
    plugin files.

There are no settings for this plugin, it will work straight away.

== Frequently Asked Questions ==

= How do I translate to other languages? =

Just take the "tirt.pot" file located in the subdirectory "translations" under the plugin's root directory and add 
the translated string in the "msgstr" line between the '"'.   
Save the edited file as "tirt-[langCode].po" and convert it to a .mo file with gettext.  

Or use poedit: Load the tirt.pot file as new catalog, translate the strings and 
save it as "tirt-[langCode].po", poedit will automatically create a .mo file.

[langCode] is the language code, such as "en_EN" or "de_DE".

== Screenshots ==

1. Relative comment date string on my blog

== Changelog ==

= 0.3 =
 - new: shortcode tag [reltime]

= 0.2 =
 - fixed: The date was not validated. (see upgrade notice)
 - new: Error handling. Now uses WP_Error objects to reflect errors. 
 - new: Two filter hooks: *tirt_get_string* and *tirt_get_array* 

= 0.1 =
 - Beta-Release

== Upgrade Notice ==

= 0.2 =

-   The date was not validated. So passing an invalid date format could break
    your whole blog, due to a neverending loop. 
    
    There's no need to upgrade, if you don't use "tirt_get_string" or "tirt_get_array"
    directly. - Unless you would like to use the new features (namely: filter hooks.) 

== Usage ==

= General =
All template tags accepts one optional parameter which specifies how many
relative time string parts should be in the string. This parameter must be
an integer between __1__ and __7__ inclusively. 

The default value is __2__.

To prevent strings like "1 year, 3 hours and 2 minutes, parts that have the value __0__
and all following parts are stripped from the string, regardless of the value of 
the optional parameter.

**Examples**:
 - the_date_relative(1)
 
   "1 year", "3 days"
   
 - the_date_relative(3)
 
   "1 year, 2 months and 4 weeks", "12 days, 3 hours and 37 minutes", "4 days"
   
= Template Tags = 

- **the_date_relative**

  displays the relative post date string
  
  **Usage**: `<?php the_date_relative($parts) ?>`
  
  
- **get_the_date_relative**

  gets the relative post date string
  
  **Usage**: `<?php $rel = get_the_date_relative($parts) ?>` 
    
- **the_modified_date_relative**

  displays the relative post modified date string
  
  **Usage**: `<?php the_modified_date_relative($parts) ?>`
  
  
- **get_the_modified_date_relative**

  gets the relative post modified date string
  
  **Usage**: `<?php $rel = get_the_modified_date_relative($parts) ?>`
  
    
- **comment_date_relative**

  displays the relative comment date string
  
  **Usage**: `<?php comment_date_relative($parts) ?>`
  
  
- **get_comment_date_relative**

  gets the relative comment date string
  
  **Usage**: `<?php $rel = get_comment_date_relative($parts) ?>`

  
= Additional Functions =
If, for whatever reason, you have a date which is not covered by the tags above, that you
want to show as a relative string, you can use the following functions:

- `<?php tirt_get_string($date, $parts) ?>`

  gets a relative date string

  **Parameters**:

  - _$date_ (string) the date to show relative in mysql DateTime format
  - _$parts_ (int) (optional) How many parts to show maximal (Default: 2)

- `<?php tirt_get_array($date) ?>`

  gets relative date parts as array in the form:   
  `array(
      'year' => <value>,
      'month' => <value>,
      'week' => <value>,
      'day' => <value>,
      'hour' => <value>,
      'minute' => <value>,
      'second' => <value>
  );`
  
  **Parameters**:
  
  - _$date_ (string) the date to get the relative parts in mysql DateTime format
  
= Filter Hooks =

On general information how to use filter hooks, please see the 
[Funtion Reference](http://codex.wordpress.org/Function_Reference/add_filter "Wordpress Codex:add_filter")

- *tirt_get_array*

  applied to the date array at the end of **tirt_get_array**.  
  Can be used to modify the values of the array.
  
  **NOTE**: The filter using this hook **MUST** return an array with the same
  keys as the input array.

- *tirt_get_string*

  applied to the relative time string.  
  Can be used to i.e append or prepend text to the string, If you do not want
  to do that on every occurence of the template tag in your theme files.
  
= Shortcode Tag =

The shortcode tag [reltime] lets you display relative time string in posts and pages.

The content of the shortcode tag is passed to phps' function "[strtotime](http://php.net/strtotime)", so any 
string that this function supports, could be used.
  
It has two optional attributes: 
 
  - format: Formats the provided date to be displayed in the title-attribute of the span 
    surrounding the relative time string. The passed value is passed on to the 
    wordpress function "date_i18n".
    
  - parts: How many relative time string parts should be displayed.
  

 **Examples**:
 
 - [reltime]2011-02-03[/reltime]   
   output similar to: &lt;span title="2011-02-03"&gt;14 days and 3 hours&lt;/span&gt;
   
 - [reltime format="j. F Y"]2011-02-03[/reltime]  
   output similar to: &lt;span title="3. Feb 2011"&gt;14 days and 3 hours&lt;/span&gt;
   
 - [reltime parts="3"]2011-02-03[/reltime]   
   output similar to: &lt;span title="2011-02-03"&gt;14 days, 3 hours and 18 minutes&lt;/span&gt;
   
 - [reltime format="d.m.Y" parts="4"][/reltime]   
   output similar to: &lt;span title="03.04.2011"&gt;14 days, 3 hours, 18 minutes and 23 seconds&lt;/span&gt;




 