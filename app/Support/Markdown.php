<?php

namespace App\Support;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;

class Markdown
{
    public static function convertToHtml(string $text) : string
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new DisallowedRawHtmlExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension);
        $environment->addExtension(new TaskListExtension());

        // Version > 1.4 needed
        // $environment->addExtension(new HeadingPermalinkExtension());
        // $environment->addExtension(new MentionExtension());
        // $environment->addExtension(new FootnoteExtension());

        $converter = new CommonMarkConverter([
            'allow_unsafe_links' => false,
        ], $environment);

        return $converter->convertToHtml($text);
    }
}