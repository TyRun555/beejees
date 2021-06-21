<?php

namespace service;

use core\App;

class Pagination
{
    public $pageSize = 10;
    public $totalItems = 0;
    public $pageParam = 'page';
    public $pagesCount = 5;

    public function __construct(?int $pageSize = null, ?int $totalItems = null, ?string $pageParam = null, int $pagesCount = null)
    {
        $this->pageSize = $pageSize ?? $this->pageSize;
        $this->totalItems = $totalItems ?? $this->totalItems;
        $this->pageParam = $pageParam ?? $this->pageParam;
        $this->pagesCount = $pagesCount ?? $this->pagesCount;
    }

    public function getTotalPages()
    {
        return ceil($this->totalItems / $this->pageSize);
    }

    public function getCurrentPage()
    {
        return (int)App::$app->get($this->pageParam) ?: 1;
    }

    private function getLink(int $page): string
    {
        $url = '?' . $_SERVER['QUERY_STRING'];
        $oldQueryParam = $this->pageParam . '=' . $this->getCurrentPage();
        $newQueryParam = $this->pageParam . '=' . $page;
        return stristr($url, $oldQueryParam)
            ? str_replace($oldQueryParam, $newQueryParam, $url)
            : $url . '&' . $newQueryParam;
    }

    public function renderPagination()
    {
        $totalPages = $this->getTotalPages();
        if ($totalPages == 0) {
            return;
        }
        $currentPage = $this->getCurrentPage();
        echo "<nav aria-label='Пагинация'><ul class='pagination'>";
        if ($currentPage > 1) {
            echo '<li class="page-item">
                      <a class="page-link" href="' . $this->getLink(1) . '" aria-label="Первая">
                          <span aria-hidden="true">&laquo;&laquo;</span>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="' . $this->getLink($currentPage - 1) . '" aria-label="Предыдущая">
                          <span aria-hidden="true">&laquo;</span>
                      </a>
                  </li>';
        }
        for ($page = 1; $page <= $this->pagesCount && $page <= $totalPages; $page++) {
            $pageNumber = $currentPage < $this->pagesCount ? $page : $page + $currentPage - $this->pagesCount;
            if ($this->getCurrentPage() == $pageNumber) {
                echo "<li class='page-item active' aria-current='page'>
                          <a class='page-link' href='#'>{$pageNumber}</a>
                      </li>";
            } else {
                echo "<li class='page-item' aria-current='page'>
                          <a class='page-link' href='" . $this->getLink($pageNumber) . "'>{$pageNumber}</a>
                      </li>";
            }
        }
        if ($currentPage < $totalPages) {
            echo '<li class="page-item">
                      <a class="page-link" href="' . $this->getLink($currentPage + 1) . '" aria-label="Следующая">
                          <span aria-hidden="true">&raquo;</span>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="' . $this->getLink($totalPages) . '" aria-label="Последняя">
                          <span aria-hidden="true">&raquo;&raquo;</span>
                      </a>
                  </li>';
        }
        echo "</ul></nav>";
    }

}