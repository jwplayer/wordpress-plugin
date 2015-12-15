=== JW Player for Wordpress ===
Contributors: LongTail Video
Tags: jwplayer, jw, player, jwplatform, video, media, html5
Requires at least: 4.3
Tested up to: 4.4
Stable tag: 0.9.0 beta
License: GPLv3

Upload and embed videos with your JW Player account to seamlessly integrate video into your Wordpress website.

== Description ==

**BE AWARE: The plugin is in beta. We've tested it locally, but because of the myriad of different Wordpress setups it could be possible that you run into issues. If you do run into an issue we encourage you to [report this issue in the GitHub mirror](https://github.com/jwplayer/wordpress-plugin/issues) of this plugin. Thank you for your help.**

This plugin will give you the power to use videos, playlists, and players from your JW Player account within Wordpress. You will also be able to track the performance of your content with JW Player’s dashboard analytics.


= Key Features =

* What it does:
    - Allows you to easily insert media (including playlists) into your website from your JW Player account.
    - Allows you to place players from your JW Player account into your website.


* What is does NOT do:
    - Replace the JW Player dashboard
    - Allow you to create or manage players or media objects from within the plugin (these actions happen within the JW Player dashboard)

* Additional features:
    - You may also sync Wordpress-hosted media to your JW Player account (as externally-hosted media)

[Sign up for a free JW Player account!](http://www.jwplayer.com/pricing/)


= Documentation =

Full documentation on installation, setup and getting started can be found on
our [Support Site](http://support.jwplayer.com/).

If you have any questions, comments, problems or suggestions please post on our
[User Forum](http://support.jwplayer.com/).

= Issues & Contributions =

This plugin is open source and we strongly encourage users to contribute to the plugin's development. If you find a bug or another issue, please [report it on the plugin's GitHub mirror](https://github.com/jwplayer/wordpress-plugin/issues) and if you would like to suggest improvements feel free to open a pull request.


== Installation ==

1. Unpack the zip-file and put the resulting folder in the wp-content/plugins
   directory of your Wordpress install.
2. Login as Wordpress admin.
3. Go the the plugins page, the JW Player plugin should be visible.
   Click "activate" to enable the plugin.
4. Click the "authorize plugin" link in the notification to authorize your
   plugin.
5. Change the settings to your liking. Don't forget to enable secure content in
   your JW Platform account if you want to make use of the signed links.
   It is also possible to enable the widget as a box inside the authoring
   environment, in addition to the "Add media" window.


== Screenshots ==

1. Overview of available plugin settings.


== Frequently Asked Questions ==

= Does this plugin replace the old JW Player Plugin for Wordpress? =

Yes, it does. You cannot run both plugins at the same time. However, you can import your referenced media, your players and your playlists from the old plugin into your JW Player account.

= Does this plugin work with caching solutions like WP-Supercache? =

Yes, it does. However, you should disable the signing functionality of the
plugin, since the caching might interfere with the signing timeout logic. Simply
go to Settings > JW Player and set the signing timeout to 0.

= Can I search through only my playlists? =

Yes, you can. In order to do this, simply write "pl:" (without the quotes) in front of your search query in the widget.

= I've found an issue with the plugin, what should I do? =

We're sorry that you've found an issue. Could you [report the issue in the plugin's the GitHub mirror](https://github.com/jwplayer/wordpress-plugin/issues).

= I have a suggestion to make the plugin better. =

That's great. Tell us about it and open a pull request on [our GitHub mirror of the plugin](https://github.com/jwplayer/wordpress-plugin/).

== Changelog ==

= 0.9 beta =

* Initial beta release.

== Upgrade Notice ==

Please remember that this plugin replaces our old JW Platform and our old JW Player for Wordpress plugins and it should not be activated at the same time.

