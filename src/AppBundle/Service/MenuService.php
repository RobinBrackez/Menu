<?php


namespace AppBundle\Service;


use AppBundle\Entity\Menu;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\ORM\EntityManager;

class MenuService
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    /** @var Menu[] $menus */
    private $menus;

    /**
     * MenuService constructor.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->menus = [];
    }

    public function getActiveMenus()
    {
        return $this->menus;
    }

    /**
     * Returns every date between two dates as an array
     * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
     */
    private function getDateRange()
    {
        $startDate = new DateTime();
        $startDate->add(DateInterval::createFromDateString('yesterday'));

        $endDate = new DateTime();
        $endDate->add(new DateInterval("P10D"));

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        $range = [];
        foreach ($dateRange as $date) {
            $range[$date->format("Y-m-d")] = $date;
        }

        return $range;
    }


    /**
     * @param $dates
     * @param $menus
     */
    public function createMenusForDates()
    {
        $dates = $this->getDateRange();
        /** @var Menu[] $menus */
        $menus = $this->getMenuRepository()->findAll();
        foreach ($dates as $dateString => $date) {
            $found = false;
            foreach ($menus as $menu) {
                if ($menu->getDate()->format('Y-m-d') === $date->format('Y-m-d')) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $menu =$this->createMenuForDate($date);
            }
            $this->menus[] = $menu;
        }
    }


    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getMenuRepository()
    {
        return $this->entityManager
            ->getRepository(Menu::class);
    }

    /**
     * @param $date
     * @return Menu
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createMenuForDate($date)
    {
        $menu = new Menu();
        $menu->setDate($date);
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $this->entityManager->persist($menu);

        // actually executes the queries (i.e. the INSERT query)
        $this->entityManager->flush();
        return $menu;
    }
}