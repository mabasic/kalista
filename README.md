# Kalista

[![Build Status](https://travis-ci.org/mabasic/kalista.svg)](https://travis-ci.org/mabasic/kalista) [![Latest Stable Version](https://poser.pugx.org/mabasic/kalista/v/stable.svg)](https://packagist.org/packages/mabasic/kalista) [![Total Downloads](https://poser.pugx.org/mabasic/kalista/downloads.svg)](https://packagist.org/packages/mabasic/kalista) [![Latest Unstable Version](https://poser.pugx.org/mabasic/kalista/v/unstable.svg)](https://packagist.org/packages/mabasic/kalista) [![License](https://poser.pugx.org/mabasic/kalista/license.svg)](https://packagist.org/packages/mabasic/kalista)

![](http://news.cdn.leagueoflegends.com/public/images/pages/kal/img/kal-ghost.png)

**Movies and TV shows file organizer helper.** 

Kalista moves movies and shows to destination folder so that [Plex media server](https://plex.tv/) can process them correctly.

## Installation

```
composer global require mabasic/kalista
```

## Usage

*Process files with [Filebot](http://www.filebot.net/) before running the commands.*

**Recommended formats:**

For movies use `{n} {[y, certification, rating]}{' CD'+pi}` format.

For shows use `{n} - {s00e00} - {t}` format.

### Move movies

```
kalista move:movies source destination
```

It moves movies from source to destination. Each movie is moved to its own folder. 

Eg. `Air [2015, PG-13, 4.4].avi` goes to `Air/Air [2015, PG-13, 4.4].avi`.

### Move shows

```
kalista move:shows source destination
```

It moves shows from source to destination. Each show is moved to its own folder. 

Eg. `Penny Dreadful - 1x01 - Night Work.mp4` goes to `Penny Dreadful/Penny Dreadful - 1x01 - Night Work.mp4`.

## Credits

- The cool image with Kalista on top is from [Kalista, the Spear of Vengeance](http://na.leagueoflegends.com/en/champion-reveal/kalista-spear-vengeance-revealed) revealed website.
