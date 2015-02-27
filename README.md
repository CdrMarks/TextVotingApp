TextVotingApp
=============

A text-based voting system (like on American Idol) using Twilio

Written for Engineer of the Year, a talent show held at the
University of Missouri-Kansas City annually during Engineers' Week
during the last week of February.

Originally "Mr. Engineer" started off as a nerd beauty pageant. There was nerdy
wear, formal wear, trivia, talent, and a popular vote.  The popular vote was done
with paper ballots and was full of rampant cheating. I created this program to
help tally votes as well as to curb cheating during the competition when the audience
voted for their favorite contestant.

After a while, Mr. Engineer turned into a legit talent show, so it was renamed to
Engineer of the Year to support women who wanted to compete as well.

This project has been fun, but also has been very hacked together over the years.
It was written over the course of a few days back in 2013, and received some
revisions in 2014 and 2015. It should be a LOT cleaner and easier to set up and
configure now.
1) Run the SQL queries in the .sql file into your MySQL database.
2) Upload the contents of the public directory where you want it to be publicly
   accessible to the public (or a laptop hooked up to a projector).
3) Edit the mysql.php file with your username/password/host/database.
4) Edit the database's textvote_choices table with the voting choices as well as
   linking pictures of the items or people you're voting on.
5) Edit index.php to reflect your HTML.  (this is probably the ugliest step as it
   has code and HTML and CSS all mushed together. Like I said, hacked together...)
6) Upload the contents of the private directory where it won't be found. These are
   the files you need to set up in Twilio when a user calls or texts (SMS) the
   number you set up.
7) Edit the databases's textvote_settings table. Make sure the phonenumber row has
   the phone number (ex: (816) 123-GEEK) and realphonenumber row has the real
   phone number (ex: (816) 123-4335). These show up on the index.php page.
8) Go to the admin.php file to start/stop the timer.  (I'd recommend renaming this
   file also for security purposes, or setting an .htaccess password on it.)