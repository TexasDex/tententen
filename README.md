# TenTenTen
Event RSVP script, copyright 2010 by Gordon Dexter (gordon@texasdex.com), released under GPLv2 or later.

## Summary
This script allows you to collect RSVPs from your guests over the internet.  You must create an invitation, associate guests with that invitation, and when a guest views their invitation page they can specify who will and who won't be attending.


## Backstory
For my wedding I put together an RSVP script that would allow guests to respond to their invitation through our wedding website.  I looked all over the net but couldn't find an RSVP script that quite suited my needs, and figured I'd work from scratch.  It wasn't a huge effort, just a few hours on my part, but I thought the community might benefit from my publicizing it.  I called it 'tententen' because that was the date of my wedding.  I'd love to hear from people who successfully use it.

## Installation

TenTenTen is not yet terribly polished: The install process is basically

* Untar files into a folder in your webserver's wwwroot directory
* Create a db and run tables.sql
* Fill in the db user and password in the database.php file
* Add your own custom CSS and modify strings as desired
* Manually create or import a guest list into the SQL tables in the correct format

You'll have to personalize it and create db entries for each of your invitations and guests.


## Benefits

A few things which make this different from other RSVP scripts I found, and especially make it well-suited for very formal events such as a wedding:

* It makes a distinction between an individual guest and an 'invitation', which may cover a whole family, so a household can RSVP all at once.
* It is designed to have your invitations already programmed in, so guests may simply search for their invitation with their name and some identifying piece of information to prevent mischief.  I used the house number, which provides a bare minimum of security, but you could use zip code, part of their phone number, or even a PIN that you include with the invitation if you wanted.  This limits abuse by not allowing just anyone to RSVP.
* It has an event table that represents a log of changes to the invitation table, to keep track of when people respond.


## Downsides

* Currently, must be installed, configured, and customized by hand
* You have to enter in the guest list beforehand (this works fine for formal events like weddings, but I realize it may not be suited for much else)
* You have to do the graphic design yourself
* The administration and results viewing was done in PhpMyAdmin, so:
* There's no page for results, although I have included a few SQL queries that show useful reports.
* There's no page for adding new guests/invitations, although it would be easy to make


Any feedback is welcome.  I can't guarantee support but may answer questions if I can.

