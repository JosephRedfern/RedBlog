NOTE
====
This is here for historical reasons, and is seriously outdated. All about the frameworks now, yo'! 



RedBlog
===============
This is my CM1102 (Web Applications) coursework. I have decided to open source it under the GNU General Public License - although I didn't choose that license for any particular reason.

You might have guessed from the Name, but RedBlog is a Blogging Engine written in PHP/MySQL. It supports posts, pages, comments and categories. It also created an RSS feed for you, supports Search, and rudimentary pagination.
 
While I dont think there are any GAPING security flaws, I am by no stretch of the imagination an expert. While you probably wouldn't WANT to use this (even if it was bug-free & secure), you shouldn't do without first checking through the code, and making sure that it's satisfactory. Please feel free to add a pull request.

Installation
---------------
There is no formal installer. Just copy the files to your web server, edit the settings.php file to match your mysql database details & the other couple of options, and you should be good to go.

The redblog.sql file comes with an account for you to use - the username is "demo", and the password is... suprise suprise, "password". You should get rid of this account ASAP (or at the very least, change the password!).
