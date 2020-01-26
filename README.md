# mp3-repo
A php-based Webfront to index MP3 Files based on der ID3 Tags.
Implementing a Search over all of the configured Tags.

## ToDo:
 - [ ] make the already indexed entries editable to allow correction of mistakes (Maybe even update the corresponding ID3 Tags in the .mp3)
 - [ ] allow the mass-updating of entries with i.e. excel lists with corresponding fields.
- [ ] Missing ID3 Tags:
  - [ ] comment

## Installation:
  * clone this repo into your WWW-Root
  * Place ID3-Parser inside ./src/ID3Parser/
  * configure your MP3 folder inside ./src/indexer.php