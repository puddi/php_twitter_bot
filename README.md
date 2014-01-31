php_twitter_bot
===============

a short php twitter bot, intended to be used w/ crons on a generic LAMP stack.

There's two crucial parts to this thing - the web interface, and the actual script that sends stuff to Twitter. The former is represented by ```index.php```, which you (the user) should access via any normal web browser. This'll give you an actual GUI to work with the raw text file, ```tweets.txt```, that's storing the future tweets.

From there, any requests to actually send stuff to Twitter is handled by ```php/postTweet.php```. By default, when called, this script will pick the next tweet in the queue, and post it to the Twitter account associated with the credentials/tokens given. This means that you can set up a ```cron``` to call ```postTweet.php``` on any given interval, and it will automatically fire a new tweet in the queue towards Twitter.

Known issues (that will hopefully get fixed!), future ideas:

* Better UI for the GUI
* Implement some form of UI for setting up crons, though this might be impossible
* Right now, the bot looks at ```\n\n``` as a tweet separator. This should probably be changed to something else
* Some better form of logging on ```oldTweets.txt```
* Selecting a tweet from a pre-determined set of possible tweets (i.e., random number gen)
