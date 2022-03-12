<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Console;

use CodeSinging\PinAdmin\Exception\AdminException;
use CodeSinging\PinAdmin\Foundation\Factory;
use CodeSinging\PinAdmin\Foundation\Manager;
use CodeSinging\PinAdmin\Support\Console\Command;
use Illuminate\Support\Str;

class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Manager::LABEL . ':create {name}';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Create a PinAdmin application';

    /**
     * Execute the console command.
     *
     * @throws AdminException
     */
    public function handle()
    {
        if (!Manager::exists($name = Str::snake($this->argument('name')))) {
            $this->title('创建 PinAdmin 应用......');
            $factory = new Factory($name);
            $factory->create();
            $this->info(sprintf('创建应用[%s]成功', $name));
        } else {
            $this->error(sprintf('应用[%s]已经存在', $name));
        }
    }
}
