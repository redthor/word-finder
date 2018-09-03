# word-finder

## Limitations

* Does not support multi-byte characters, so foreign lone words are not
 included.
* Pronouns are not includes, so no names of people, countries and so on.

## Assumptions

* It is assumed that all consonants (where there are more than once character)
  will not return a result. So no processing is conducted under these conditions.
* We have assumed that multi-byte characters outside of scope. These will not be
  accepted.
* It is assumed that a plain/text response is adequate for the endpoints.

## Strategy
### Permutations and Dictionary Scan

The system has two strategies for finding words:

1. Find all permutations of the characters, look up the permutations in the
   dictionary, and return results;
1. Recurse through the dictionary finding words that can be made from the
   characters supplied.

The number of permutations, especially considering the power set of characterss
passed in (e.g. 'abc' includes 'a', 'b', 'c', 'ab' etc., as well as the permutations
relating to all three characters) grows at a rate greater than n! (factorial) where n = number
of characters. So the first strategy is impractical and slow when n > 6.
However, when n <= 6 it is faster than recursing through the whole
dictionary. Of course, this is sensitive to the size of the dictionary and ours
is relatively small. With a bigger dictionary the permutation model may still be
better when n = 7, though n = 8 is unlikely.

Therefore the system employs the first strategy for 0 < n < 7 and then uses the
second.

### Dictionary Types

Additionally there are 3 dictionary types to optimise the solution:

1. \SplFixedArray dictionary;
1. Trie-based dictionary;
1. RadixTrie-based dictionary;

The fixed array dictionary is best for the recursion strategy while the Trie-based
dictionary is used in concert with the permutation builder to avoid a full scan.

### Delegate Pattern

The WordFinder service has a set of delegates 

## Endpoints

Two enpoints are available, this will respond with an OK message:

```
/ping
```
This, given a set of characters it will return valid words from the British English
Linux dictionary:

```
/word-finder/:characters
```
The endpoint will fail for invalid characters including numbers and multi-byte
characters.

## Console Commands

Two console commands are available.

### Word Finder
This command lets you run the word finder from the command line printing the
time spent and letting you control the strategy:
```
./bin/console app:find-words <characters> \
    --dictionary-type=array
    --force-dictionary-search

  <characters>        - Required characters to find words from.
  --dict-type         - Optional. Defaults to 'array'. Allowed types are: array | trie | radix
  --force-dict-search - Optional. Full dictionary search will always occur when characters length is greater than 6. You can force it to happen when <= 6 with this flag.
```

### Run All Word Finders
This command will run through all the word finder strategies:
```
./bin/console app:run-all-word-finders <characters>
```
Note if you pass more than 6 chars it will not be able to run all the tests
because the permutation strategy won't accept it.


## Running The Tests

The project uses [atoun](http://atoum.org/) as it's testing framework. To run
the tests:

```
./vendor/bin/atoum -d tests
```

## Notes

* The British English dictionary supplied by Linux is used. It includes every
  single letter as a word. These have been kept so you will find results that
  include 's', 't' and so on.

- The service just uses the in-built php web server (`./bin/console server:run`)
  a more sophisticated implementation could include nginx.

## Resources

The second strategy, of using the letters of the characters and recursing
through the dictionary comes from Pham Trung:
https://stackoverflow.com/a/25298960/5637853

The permutation power set builder is based on code supplied by 
dirk.avery@gmail.com: http://au2.php.net/manual/en/function.shuffle.php#90615

The Trie implementation is provided by Mark Baker:
https://github.com/MarkBaker/Tries


## TODO

- The Trie structure is optimised for searching - it is not a good structure
  for recursion, that said, the particular implementation of recursion supplied
  is a poor one. If we wanted to know more about using a Trie Tree with
  recursion a better implementation would be worthwhile (though we'd probably
  still just confirm that trie-tree recursion isn't as efficient as the
  \SplFixedArray).

- Change the delegate pattern to a chain. That way we can insert a cache into
  the chain and providers won't need to know. The word 'provider' might have
  been better than 'delegate'.

- Having the dictionaries defined in PHP is a bit messy but works fine for this
  number of words. It has the draw back that an update to the dictionary would
  require an update to the code. It also makes the code analysis tools slower.
  A better solution would be to draw up the dictionary from a separate location
  and cache it.
