<p align="center"><a href="http://www.hostnet.nl" target="_blank">
    <img width="400" src="https://www.hostnet.nl/images/hostnet.svg">
</a></p>

The CSS sniffer is build to help improve (and maintain) a consistent code style in CSS and LESS files. It will reduce time figure out another style by enforcing rules. So no longer worrying about tabs vs. spaces, single vs. double quotes or 3 of 6 hex values for colors.

The tool is pretty straight forward, simply run it with the file you would like to inspect. So if you have a css file like so:
```css
.good { background-image: url("/foo.com"); }
.bad { background-image: url('/foo.com'); }
```
Simply run the tool and you will get the following output.
```
$ vendor/bin/css-sniff sniff quotes.less

FILE: test/Sniff/fixtures/quotes.less
--------------------------------------------------------------------------------
FOUND 1 ERROR(S) AFFECTING 1 LINE(S)
--------------------------------------------------------------------------------
 2 | Text should use " as quotes.
--------------------------------------------------------------------------------
```

Installation
------------
 * `$ composer require --dev hostnet/css-sniffer`
 * This library follows [semantic versioning](http://semver.org/) strictly.
> For now, we only have a composer installation. A single-file-executable is planned for the future.

Documentation
-------------
Basic usuage is as follows:
```
$ vendor/bin/css-sniff --help
Usage:
  sniff [options] [--] [<files>]...

Arguments:
  files                      Input file

Options:
      --format[=FORMAT]      Type of output format, default: console [default: "console"]
  -s, --standard[=STANDARD]  Code Standard to use, by default the Hostnet standard is used. This is the path to the xml file.
      --stdin                If given, this option will tell the sniffer to check the STDIN for input.
  -p, --pretty               Pretty format output
      --no-exit-code         Always return 0 as exit code, regardless of the result
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Sniffs the given input file and returns the result.
```

*Examples*:
- `vendor/bin/css-sniff`
- `vendor/bin/css-sniff sniff some/file.css`
- `vendor/bin/css-sniff sniff --format=json -p some/file.css`
- `cat some/file.css | vendor/bin/css-sniff sniff --stdin`

### Sniffing for CI
The primary focus of the sniffer is to integrate with any CI tooling. For this, it is recommended to add a `csssniff.xml.dist` to your project root. This will allow you to configure which files and directories to process when running the sniffer. A common example would be:
```xml
<?xml version="1.0"?>
<csssniffer>
    <directory>./app/styles</directory>

    <sniff rel="Hostnet" />
</csssniffer>

```
This would process the `app/styles` folder relative from the project root using the `Hostnet` standard. For more information about the xml structure, see [the ruleset configuration documentation](RULESETS.md).

### Output formatting
Multiple output formats are supported. For now there is `console` (the default), `checkstyle` and `json`. The `json` output looks as follows:
```
$ vendor/bin/css-sniff sniff --format=json -p test/Sniff/fixtures/quotes.less
{
    "totals": {
        "errors": 0
    },
    "files": {
        "test\/Sniff\/fixtures\/quotes.less": {
            "errors": 3,
            "messages": [
                {
                    "message": "One statements found and should be on one line.",
                    "source": "Hostnet\\Component\\CssSniff\\Sniff\\CurlySniff",
                    "line": 1,
                    "column": 7
                },
                {
                    "message": "One statements found and should be on one line.",
                    "source": "Hostnet\\Component\\CssSniff\\Sniff\\CurlySniff",
                    "line": 4,
                    "column": 6
                },
                {
                    "message": "Text should use \" as quotes.",
                    "source": "Hostnet\\Component\\CssSniff\\Sniff\\QuoteTypeSniff",
                    "line": 5,
                    "column": 27
                }
            ]
        }
    }
}

```
> The `-p` is only a pretty format, this is optional but more readable.

### `STDIN` input
The sniffer can also read from the `STDIN`. This can be usefull when intergrating the tool in an IDE where you might not have a file but want to pass the contents of an editor. Make sure to add the `--stdin` to tell the sniffer to read the `STDIN`. You can also pass a file to allows for you matching rules to work.
```
$ cat test/Sniff/fixtures/quotes.less | vendor/bin/css-sniff sniff --format=json -p --stdin test/Sniff/fixtures/quotes.less
{
    "totals": {
        "errors": 0
    },
    "files": {
        "test\/Sniff\/fixtures\/quotes.less": {
            "errors": 3,
            "messages": [
                {
                    "message": "One statements found and should be on one line.",
                    "source": "Hostnet\\Component\\CssSniff\\Sniff\\CurlySniff",
                    "line": 1,
                    "column": 7
                },
                {
                    "message": "One statements found and should be on one line.",
                    "source": "Hostnet\\Component\\CssSniff\\Sniff\\CurlySniff",
                    "line": 4,
                    "column": 6
                },
                {
                    "message": "Text should use \" as quotes.",
                    "source": "Hostnet\\Component\\CssSniff\\Sniff\\QuoteTypeSniff",
                    "line": 5,
                    "column": 27
                }
            ]
        }
    }
}
```

License
-------------
The `hostnet/css-sniffer` is licensed under the [MIT License](https://github.com/hostnet/css-sniffer/blob/master/LICENSE), meaning you can reuse the code within proprietary software provided that all copies of the licensed software include a copy of the MIT License terms and the copyright notice.

Get in touch
------------
 * Our primary contact channel is via IRC: [freenode.net#hostnet](http://webchat.freenode.net/?channels=%23hostnet).
 * Or via our email: opensource@hostnet.nl.
