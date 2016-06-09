<?php
namespace Insomnia\Cms\Commands;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use File;

class UpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:update';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insomnia Cms Update command';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('## INSOMNIA CMS Update ##');

        // $this->call('asset:publish', array('package' => 'insomnia/cms' ) );
        $this->call('asset:publish', array('--bench' => 'insomnia/cms' ) );

 
    }
}