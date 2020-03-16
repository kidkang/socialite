<?php 
namespace Yjtec\Socialite\Listeners;
use Yjtec\Socialite\Events\SocialiteLogUser;
use Yjtec\Socialite\Models\Socialite;
class SocialiteLogUserListener{
    /**
     * 创建事件监听器。
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * 处理事件
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(SocialiteLogUser $event){

        $class = 'Yjtec\\Socialite\\Models\\'.ucfirst($event->driver);

        $clientId = $event->clientId;

        $user = (new $class)->create(array_merge(
            $event->user,
            ['client_id'=>$clientId]
        ));

        $social = (new Socialite)->create([
            'socialitetable_id' => $user->id,
            'socialitetable_type' => $class
        ]);

        
    }
}

?>