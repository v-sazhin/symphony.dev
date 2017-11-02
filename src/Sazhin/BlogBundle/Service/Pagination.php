<?php

namespace Sazhin\BlogBundle\Service;


use Knp\Component\Pager\Paginator;

class Pagination
{
    /**
     * @var Paginator
     */
    private $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function paginate(array $collection, int $currentPage, $defaultSortFieldName = 'id', $defaultSortDirection = 'desc')
    {
        return $this->paginator->paginate(
            $collection,
            $currentPage,
            50, ['defaultSortFieldName' => $defaultSortFieldName, 'defaultSortDirection' => $defaultSortDirection]
        );
    }

}