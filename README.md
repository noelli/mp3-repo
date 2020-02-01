# mp3-repo
A php-based Webfront to index MP3 Files based on der ID3 Tags.
Implementing a Search over all of the configured Tags.

## ToDo:
 - [ ] make the already indexed entries editable to allow correction of mistakes
   - [ ] Maybe even update the corresponding ID3 Tags in the .mp3
 - [ ] allow the mass-updating of entries with i.e. excel lists with corresponding fields.
- [ ] Missing ID3 Tags:
  - [ ] comment

## Installation:
  * clone this repo into your WWW-Root: `git clone https://github.com/noelli/mp3-repo.git`
  * update permissions so that your webserver can create the index and db files: `chown -R www-data mp3-repo`
  * go into the mp3-repo folder: `cd mp3-repo`
  * get the required libraries with composer: `composer install`
  * configure your folders inside ./indexer.php: `vim indexer.php`
