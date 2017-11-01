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
$ bin/css-sniff sniff test/Sniff/fixtures/quotes.less

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
The tool is still under heavy development, but if you are willing to give it a go follow the installtation instructions. There are various command line options for the tool.

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

### Defining your own standard
If you would like to create your own standard, you can do this by creating your own `xml` file and passing it with `-s` or `--standard`. An exmple of a standard file is:
```xml
<?xml version="1.0"?>
<csssniffer>
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ArgsSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ClassSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ColorSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\CurlySniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\EmptyCommentSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\EmptyLinesSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\EmptySniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\IdSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\IndentSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\QuoteTypeSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\SemicolonSniff" />
    <sniff class="\Hostnet\Component\CssSniff\Sniff\VariableSniff" />
</csssniffer>
```
But you can add your own classes, as long as they extend the [`SniffInterface`](https://github.com/hostnet/css-sniffer/blob/master/src/SniffInterface.php). Any arguments defined in the `xml` are passed as constructor arguments to the sniff.

### Output formatting
Multiple output formats are supported. For now there is `console` (the default), `checkstyle` and `json`. The `json` output looks as follows:
```
$ bin/css-sniff sniff --format=json -p test/Sniff/fixtures/quotes.less
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
$ cat test/Sniff/fixtures/quotes.less | bin/css-sniff sniff --format=json -p --stdin test/Sniff/fixtures/quotes.less
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
