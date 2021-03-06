<!DOCTYPE html>
<html>
<head>
    <title><?= $this->className; ?> - <?= $this->message; ?></title>
    <style>
        body, code {
            font: medium/1.5em monospace;
        }
        h1, h2 {
            font-size: 1.25em;
        }
        table {
            width: auto;
            border-collapse: collapse;
            overflow: hidden;
        }
        td {
            vertical-align: top;
        }
        td:nth-child(1) {
            position: relative;
            width: 3em;
            padding: 0 0.5em 0 0;
            text-align: right;
            color: #999;
            border-right: 1px solid #999;
            z-index: 1;
        }
        td:nth-child(2) {
            position: relative;
            padding: 0 0 0 0.5em;
            z-index: 2;
        }
        td, td span {
            white-space: nowrap;
        }
        td span.mark {
            position: relative;
            font-weight: bold;
            color: #f00;
        }
        td span.mark:after {
            content: \' . \';
            position: absolute;
            top: -0.2em;
            left: -2em;
            width: 10000em;
            background: #f00;
            opacity: 0.25;
        }
    </style>
</head>
<?php
function lineNum($source, $mark = null)
{
    $lines = array();
    foreach (explode('<br />', $source) as $i => $line) {
        $lines[] = '<span ' . ($i + 1 == $mark ? 'class="mark"' : '') . '>' . ($i + 1) . '</span>';
    }
    return sprintf('<table><tr><td>%s</td><td>%s</td></tr></table>', implode('<br />', $lines), $source);
}
?>
<body>
<h1><?= $this->className; ?> - &quot;<?= $this->message; ?>&quot;</h1>
<div>
    <h2>File: <?= $this->file; ?>:<?= $this->line; ?></h2>
    <?= lineNum(highlight_file($fileBody, true), $line); ?>
</div>
<div>
    <h2>Trace</h2>
    <?= $this->trace; ?>
</div>
</body>
</html>