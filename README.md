# Kalista

[![Build Status](https://travis-ci.org/mabasic/kalista.svg)](https://travis-ci.org/mabasic/kalista) [![Latest Stable Version](https://poser.pugx.org/mabasic/kalista/v/stable.svg)](https://packagist.org/packages/mabasic/kalista) [![Total Downloads](https://poser.pugx.org/mabasic/kalista/downloads.svg)](https://packagist.org/packages/mabasic/kalista) [![Latest Unstable Version](https://poser.pugx.org/mabasic/kalista/v/unstable.svg)](https://packagist.org/packages/mabasic/kalista) [![License](https://poser.pugx.org/mabasic/kalista/license.svg)](https://packagist.org/packages/mabasic/kalista)

![](http://news.cdn.leagueoflegends.com/public/images/pages/kal/img/kal-ghost.png)

A movie/tv show organizer command line app.

**IMPORTANT: Currently Kalista uses FileBot to rename movies, so be sure to have FileBot installed on your PC.**

## Roadmap

- [ ] Consume some API to resolve tv shows and movies.
- [ ] Maybe stop using FileBot because the integration is missing.


## Installation

```
composer global require "mabasic/kalista=0.7.*"
```

## Usage

### Orrganize Movies

```
php kalista movies:organize source destination
```

This command **moves** files from source to destination and cleans the source folder after.

**Example:**

```
kalista movies:organize "H:\wd\public\Shared Videos" H:\wd\public\movies
```

Would organize all movies from folder `Shared Videos` to folder `movies`.

**Supported extensions are: .mp4, .avi, .mkv**

**Source folder:**

```
[Movie name].[extension]
[Movie name].[extension]
[Movie name].[extension]
[Movie name].[extension]
[Movie name].[extension]
```
 
**Destination folder (output):**
 
```
[Movie name]
    [Movie name].[extension]
[Movie name]
    [Movie name].[extension]
```

**Delimiter:**

`[` is a delimiter. If it is  missing the the whole filename without the extension is used.

```
10,000 BC [2008, PG-13, 5.4].avi
```

Would produce: `10,000 BC`


```
10,000 BC.avi
```

Would produce: `10,000 BC`

### Orrganize TV shows

```
php kalista tvshows:organize source destination
```

**Example:**

```
kalista tvshows:organize "H:\wd\public\Shared TVShows" H:\wd\public\tvshows
```

Would organize all tv shows from folder `Shared TVShows` to folder `tvshows`.

**Supported extensions are: .mp4, .avi, .mkv**

**Source folder:**

```
[TV Show name - 2x04].[extension]
[TV Show name - 2x05].[extension]
[TV Show name - 2x06].[extension]
[TV Show name - 2x07].[extension]
[TV Show name - 2x08].[extension]
```
 
**Destination folder (output):**
 
```
[TV Show name]
    Serie [Serie number]
        [TV Show name].[extension]
[TV Show name]
    Serie [Serie number]
        [TV Show name].[extension]
```

**Delimiter:**

` - ` is a delimiter.

## Credits

- The cool image with Kalista on top is from [Kalista, the Spear of Vengeance](http://na.leagueoflegends.com/en/champion-reveal/kalista-spear-vengeance-revealed) revealed website.
