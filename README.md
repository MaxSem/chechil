Chechil - a PHP syntax highlighter
====================================
### Version 0.9, forked from GeSHi 1.0.8.12

Why it was forked?
------------------
GeSHi development has largely stalled, resulting in a bunch of pull requests waiting forever. Also, GeSHi's "every language defines its own styles" approach made it impossible to have a concise style, reskin their syntax highlighting to match page style, etc. I also wanted to revamp the code base by namespacing everything and generally modernizing it.

About the name
--------------
I wanted to name it [shishishi](https://en.wikipedia.org/wiki/Lion-Eating_Poet_in_the_Stone_Den) as a play on "GeSHi", however someone has already named their project like that. So I decided to call it [chechil](https://en.wikipedia.org/wiki/Chechil) because reasons (the main reason being that I like chechil).

Running unit tests
------------------
First, make sure that there composer autloads are installed:

 $ composer install

Then, assuming that you already have PHPUnit installed globally, run from the root of checkout:

 $ phpunit .

License
-------
Chechil is free software, released under the GNU GPL. Please see the COPYING file for more information.
When contributing new code please don't add your personal copyright to copyright headers beacuse this is detrimental to the idea of shared code ownership. Instead, just write "Copyright [year]" or "Copyright [year] Chechil contributors".
