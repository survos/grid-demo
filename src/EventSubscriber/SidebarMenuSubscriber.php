<?php

namespace App\EventSubscriber;


use Knp\Menu\ItemInterface;
use Survos\BootstrapBundle\Menu\MenuBuilder;
use Survos\BootstrapBundle\Traits\KnpMenuHelperTrait;
use Survos\BootstrapBundle\Event\KnpMenuEvent;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SidebarMenuSubscriber implements EventSubscriberInterface
{
    use KnpMenuHelperTrait;

    private AuthorizationCheckerInterface $security;

    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    public function onKnpMenuEvent(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $this->addMenuItem($menu, ['route' => 'app_homepage']);

        // add the login/logout menu items.
        $this->authMenu($this->security, $menu);

    }

    /*
    * @return array The event names to listen to
    */
    public static function getSubscribedEvents()
    {
        return [
            MenuBuilder::SIDEBAR_MENU_EVENT => 'onKnpMenuEvent',
        ];
    }
}
