Ruleset configuration
----------------
Rulesets are a great way to fine tune the way the sniffer works. It allows for including or excluding files from all or specific sniffs to make sure no false positives show up.

A typical ruleset would like like:
```xml
<?xml version="1.0"?>
<csssniffer>
    <directory>./app/styles</directory>
    
    <sniff rel="Hostnet" />
</csssniffer>
```
This uses all the default settings which are present in the `Hostnet` ruleset and processes all `.less` and `.css` files in the `app/styles` directory.

A more fine grained ruleset can be:
```xml
<?xml version="1.0"?>
<csssniffer>
    <directory>./htdocs/css</directory>
    <exclude-pattern>*.min.css</exclude-pattern>
    
    <sniff rel="Hostnet" />
    
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ClassSniff">
        <arg>[a-z0-9_]+</arg>
    </sniff>
    
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ColorSniff">
        <exclude-pattern>htdocs/css/old-style.css</exclude-pattern>
    </sniff>
</csssniffer>
```

This extends the `Hostnet` ruleset by changing the argument for the `ClassSniff` and by excluding the `old-style.css` from the `ColorSniff`. Additionaly it also excludes all `*.min.css` files from the sniffer.

## Defining your own standard
If you would like to create your own standard, you can do this by creating your own `xml` file and defining sniff rules. This what the definition of the `Hostnet` standard looks like.
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
The CSS sniffer comes with some built-in sniffs which can be configured using arguments. Add your own is possible as long as they extend the [`SniffInterface`](https://github.com/hostnet/css-sniffer/blob/master/src/SniffInterface.php).

## Fully annotated ruleset
For a full list of all supported elements and attributes is a fully annotated ruleset. Everything shown is supported by the css sniffer tool.
```xml
<?xml version="1.0"?>
<csssniffer
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
    xsi:noNamespaceSchemaLocation="https://hostnet.github.io/css-sniffer/2.0/schema.xsd">

    <!--
        Description of the ruleset. This is never shown anywhere but can give
        extra information about the type of sniffs configured.
     -->
    <description>A custom coding standard</description>

    <!--
        Add a file to the list of files to be processed.

        Files should be relative to the directory the ruleset file is in.
     -->
    <file>./some/file.css</file>

    <!--
        Add a directory to the sniff to be processed. This will recursively
        scan all files for matching extensions and will add them to the list of
        files to be processed.

        Directories should be relative to the directory the ruleset file is in.
     -->
    <directory>./some/directory</directory>

    <!--
        A global exclusion pattern which can be added to exclude files or
        directories from all sniffs. This will prevent sniffs from processing
        any matched file.
     -->
    <exclude-pattern>*.min.css</exclude-pattern>

    <!--
        Import an external ruleset. This can be one of the defaults (i.e.,
        "Hostnet") or can refer to another file. This will include all sniffs
        and configuration from that file.

        Any reference to file are relative to the directory the ruleset file is
        in.
     -->
    <sniff rel="Hostnet" />
    <sniff rel="./some/other/ruleset.xml" />

    <!--
        Include a sniff in this rule. This will apply the sniff to any matched
        files.
     -->
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ArgsSniff" />

    <!--
        Some sniffs can have arguments to allow more fine-grained options.
        These are passed as constructor arguments in-order.
     -->
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ArgsSniff">
        <arg>first</arg>
        <arg>second</arg>
    </sniff>

    <!--
        Exclusion patterns can be added to exclude files or directories from a
        certain sniff. This will prevent the sniff from processing any matched
        file.
     -->
    <sniff class="\Hostnet\Component\CssSniff\Sniff\ArgsSniff">
        <exclude-pattern>*.min.css</exclude-pattern>
        <exclude-pattern>src/some/file/to/exclude.css</exclude-pattern>
    </sniff>

</csssniffer>

```
