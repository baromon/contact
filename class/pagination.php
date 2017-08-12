<?php

namespace NG\Page;

class Pagination
{
    protected $curPage;
    protected $nbPage;

    protected $firstRowPrint;
    protected $lastRowPrint;
    protected $rowPerPage;
    protected $nbRow;

    /*****************************************************************************************************************/
    // General

    public function config($nbRow, $rowPerPage)
    {
        $this->nbRow = $nbRow;
        $this->rowPerPage = $rowPerPage;
        $this->nbPage = ceil($nbRow / $rowPerPage);
    }

    /*****************************************************************************************************************/
    // Set Get

    public function getCurPage()
    {
        return $this->curPage;
    }

    public function setCurPage($pageNum)
    {
        // Check and set pageNum
        if (isset($pageNum) && is_numeric($pageNum)) {
            if ($pageNum < 1)
                $this->curPage = 1;
            elseif ($pageNum > $this->nbPage)
                $this->curPage = $this->nbPage;
            else
                $this->curPage = $pageNum;
        } else
            $this->curPage = 1;

        // Set firstRowPrint
        $this->firstRowPrint = ($this->curPage - 1) * $this->rowPerPage;

        // Set lastRowPrint (less row in the last page)
        $this->lastRowPrint = $this->firstRowPrint + $this->rowPerPage;
        if ($this->lastRowPrint > $this->nbRow) {
            $this->lastRowPrint = $this->nbRow;
        }

        // Case no contact found
        if ($this->nbRow <= 0) { // Case 0 contact to print
            $this->firstRowPrint = 0;
            $this->rowPerPage = 0;
        }
        return $this->curPage;
    }

    public function getNbPage()
    {
        return $this->nbPage;
    }

    public function getRowPerPage()
    {
        return $this->rowPerPage;
    }

    public function getFirstRowPrint()
    {
        return $this->firstRowPrint;
    }

    public function getLastRowPrint()
    {
        return $this->lastRowPrint;
    }

    public function getNbRow()
    {
        return $this->nbRow;
    }
}