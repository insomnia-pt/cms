<?php
namespace Insomnia\Cms\Commands;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:install';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insomnia Cms install command';
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
        $this->info('## INSOMNIA CMS Install ##');

        // $this->call('asset:publish', array('package' => 'insomnia/cms' ) );
        $this->call('asset:publish', array('--bench' => 'insomnia/cms' ) );

        $this->call('migrate', array('--env' => $this->option('env'), '--package' => 'cartalyst/sentry' ) );

        // $this->call('migrate', array('--env' => $this->option('env'), '--package' => 'insomnia/cms' ) );
        $this->call('migrate', array('--env' => $this->option('env'), '--bench' => 'insomnia/cms' ));

        $this->call('db:seed', array('--class' => 'Insomnia\Cms\DatabaseSeeder' ));
        
        $this->call('config:publish', array('package' => 'cartalyst/sentry' ) );
    }
}