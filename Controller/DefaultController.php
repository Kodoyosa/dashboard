<?php

namespace Kodoyosa\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KodoyosaDashboardBundle:Default:index.html.twig');
    }

    /**
     * @param $route
     * @return \Symfony\Component\HttpFoundation\Response
     * building of the left side menu
     * returns array sections : Contains en array of sections and items
     * returns string route : route name of the current url
     */
    public function sidemenuAction($route)
    {
        $router = $this->container->get('router');

        $sections = $this->getDoctrine()
            ->getRepository('KodoyosaDashboardBundle:Sectionmenu')
            ->getSectionmenus();

        foreach($sections as &$section){
            $items = $this->getDoctrine()
                ->getRepository('KodoyosaDashboardBundle:Itemmenu')
                ->getItemsbySectionId($section);


            /*
             * Check if route exists or excludes it from section
             */
            foreach($items as $key => &$itemmenu) {
                if ($router->getRouteCollection()->get($itemmenu->getRoutename()) === null) {
                    unset($items[$key]);
                }
            }

            $section->items = $items;
        }

        return $this->render(
            'KodoyosaDashboardBundle:Default:sidemenu.html.twig',
            [
                'sections' => $sections,
                'route' => $route
            ]);
    }
}
