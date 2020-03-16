<?php 
namespace Yjtec\Socialite\Commands;
use Illuminate\Console\Command;
use Yjtec\Socialite\Commands\Databases\Socialite;
class DatabaseCommand extends Command{

    protected $signature = 'socialite:table ';

    protected $description = 'Socialite Talbe Command';

    public function handle(){
        dd($this->app['config']['socialite']);
        // $database = new Socialite();
        // $database->up();
    }
}