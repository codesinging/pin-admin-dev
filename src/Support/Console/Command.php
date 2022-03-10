<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Support\Console;

class Command extends \Illuminate\Console\Command
{
    /**
     * Write a title string.
     *
     * @param string $title
     * @param string $prefix
     *
     * @return void
     */
    protected function title(string $title, string $prefix = '- ')
    {
        $this->line($prefix . $title);
    }
}
