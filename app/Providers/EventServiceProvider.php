<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

use App\Models\Module;
use Auth;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
                        
            $mod = Module::whereHas('users',function($q)
            {
                $q->where('users.id',Auth::user()->id);
            })
            ->orderBy('id','ASC')
            ->get()->toTree();
            
            $trans = function($params) use (&$trans, $event)
            {
                foreach($params as $par)
                {
                    if(empty($par->parent))
                    {
                        $event->menu->add([
                            'header' => strtoupper($par->nama)
                        ]);
                        $trans($par->children);
                    }
                    else
                    {
                        $route = "";
                        if(!empty($par->param))
                        {
                            $route = route($par->route,explode('.',$par->param));
                        }
                        else
                        {
                            if($par->route != '#')
                            {
                                $route = route($par->route);
                            }
                            else
                            {
                                $route = "";                    
                            }
                        }
                        
                        $event->menu->add([
                            'text' => $par->nama,
                            'url'  => $route,
                            'icon' => $par->icon,
                        ]);
                    }
                }
            };
            $trans($mod);
        });

    }
}
