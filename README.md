# Kalista

A movie/tv show organizer command line app.

## Installation

```
composer global require "mabasic/kalista=0.1.*"
```

## Usage

```
php kalista organize:movies source destination
```

**Example:**

```
kalista organize:movies "H:\wd\public\Shared Videos" H:\wd\public\movies
```

Would organize all movies from folder `Shared Videos` to folder `movies`.

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