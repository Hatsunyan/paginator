<?php

/**
 * Created by PhpStorm.
 * User: Hatsunyan
 * Date: 17.03.2016
 * Time: 20:49
 */
namespace Hatsunyan;
Class Paginator
{
    protected $languages =
        [
            'custom' => [],
            'ru'     => [['Первая','Последняя'],['Назад','Вперёд']],
            'en'     => [['First','Last'],['Prev','Next']],
            'arrows' => [['««','»»'],['«','»']]
        ];

    //options
    protected $lang              = 'ru';
    protected $showLastFirst     = true;
    protected $showSteps         = true;
    protected $maxItems          = 6;
    protected $linkPatternStart  = '/';
    protected $linkPatternEnd    = '';
    protected $ulClass           = 'paginator';
    // vars
    protected $currentPage       = 1;
    protected $lastPage          = 1;
    protected $pagesArr          = [];
    protected $html              = null;

    /**
     * Paginator constructor.
     * @param int $items - total items
     * @param int $itemsOnPage
     * @param int $currentPage
     */
    function __construct($items = 1, $itemsOnPage = 1, $currentPage = 1)
    {
        $this->setPages($items, $itemsOnPage, $currentPage);
        return $this;
    }

    /**
     * @param int $items - total items
     * @param int $itemsOnPage
     * @param int $currentPage
     * @return $this
     */
    function setPages($items = 1, $itemsOnPage = 1, $currentPage = 1)
    {
        $this->lastPage = ceil($items / $itemsOnPage);
        $this->setCurrentPage($currentPage);
        return $this;
    }

    /**
     * @param int $page
     * @return $this
     */
    function setCurrentPage($page)
    {
        $page = $page > $this->lastPage ? $this->lastPage : $page;
        $page = $page < 1 ? 1 : $page;
        $this->currentPage = $page;
        return $this;
    }

    /**
     * @param string $pattern like /news/{p}, where {p} - page number
     * @return $this
     */
    function setUrlPattern($pattern)
    {
        $array = explode('{p}', $pattern);
        $this->linkPatternStart = $array[0];
        $this->linkPatternEnd = $array[1];
        return $this;
    }

    /**
     * show next and prev buttons
     * @param bool $show
     * @return $this
     */
    function showNextPrev($show)
    {
        $this->showSteps = $show;
        return $this;
    }

    /**
     * show last and first button
     * @param bool $show
     * @return $this
     */
    function showLastFirst($show)
    {
        $this->showLastFirst = $show;
        return $this;
    }

    /**
     * out html
     */
    function render()
    {
        if ($this->html == null) {
            $this->makeHtml();
        }
        echo $this->html;
    }

    /**
     * return html
     * @return  string|null
     */
    function getHtml()
    {
        if ($this->html == null) {
            $this->makeHtml();
        }
        return $this->html;
    }

    /**
     * @param int $items
     */
    function setMaxItems($items)
    {
        if($items % 2)
        {
            $items++;
        }
        $this->maxItems = $items;
    }

    /**
     * @param string $lang |ru|en|arrow
     * @return $this
     */
    function setLang($lang)
    {
        if(isset($this->languages[$lang]))
        {
            $this->lang = $lang;
        }
        return $this;
    }

    /**
     * set your land like ['first','last'],['prev','next'] | false,'en' | '...','ru'
     * @param array|string|bool $fistLast
     * @param array|string|bool $nextPrev
     * @return $this
     */
    function setCustomLang($fistLast, $nextPrev = false)
    {
        if($fistLast)
        {
            if(is_array($fistLast))
            {
                $this->languages['custom'] = $fistLast;
            }
            if(is_string($fistLast))
            {
                if(isset($this->languages[$fistLast]))
                {
                    $this->languages['custom'][0] = $this->languages[$fistLast][0];
                }else{
                    $this->languages['custom'][0] = [$fistLast,$fistLast];
                }
            }
        }
        if($nextPrev)
        {
            if(is_array($nextPrev))
            {
                $this->languages['custom'] = $nextPrev;
            }
            if(is_string($nextPrev))
            {
                if(isset($this->languages[$nextPrev]))
                {
                    $this->languages['custom'][1] = $this->languages[$nextPrev][1];
                }else{

                    $this->languages['custom'][1] = [$nextPrev,$nextPrev];
                }
            }
        }
        if($fistLast || $nextPrev)
        {
            $this->setLang('custom');
        }
        return $this;
    }

    /** set ul class for html
     * @param string $class
     * @return $this
     */
    function setUlClass($class)
    {
        $this->ulClass = $class;
        return $this;
    }

    protected function makeHtml()
    {
        if ($this->lastPage == 1) {
            $this->html = false;
            return $this;
        }
        $this->makeArray();
        $this->html .= '<ul class="'.$this->ulClass.'">';
        foreach ($this->pagesArr as $p) {
            $this->html .= '<li>';
            if ($p['page'] != $this->currentPage) {
                $this->html .= '<a href="' . $p['link'] . '">' . $p['title'] . '</a>';
            } else {
                $this->html .= '<a class="active">' . $p['title'] . '</a>';
            }
            $this->html .= '</li>';

        }
        $this->html .= '</ul>';
        return $this;
    }

    protected function addToArr($start, $end)
    {
        $end = ($this->lastPage < $end ? $this->lastPage : $end);
        for ($i = $start; $i <= $end; $i++) {
            $this->pagesArr[] = [
                'title' => $i,
                'link'  => $this->linkPatternStart . $i . $this->linkPatternEnd,
                'page'  => $i
            ];
        }
    }

    protected function addLast()
    {
        if(!$this->showLastFirst)
        {
            return;
        }
        $this->pagesArr[] = [
            'title' => $this->languages[$this->lang][0][1],
            'link'  => $this->linkPatternStart . $this->lastPage . $this->linkPatternEnd,
            'page'  => ''
        ];
    }

    protected function addFirst()
    {
        if(!$this->showLastFirst)
        {
            return;
        }
        $this->pagesArr[] = [
            'title' => $this->languages[$this->lang][0][0],
            'link'  => $this->linkPatternStart . '1' . $this->linkPatternEnd,
            'page'  => ''
        ];
    }

    protected function addStepBack()
    {
        if (!$this->showSteps) {
            return;
        }
        $page = $this->currentPage - 1;
        $this->pagesArr[] = [
            'title' => $this->languages[$this->lang][1][0],
            'link'  => $this->linkPatternStart . $page . $this->linkPatternEnd,
            'page'  => ''
        ];
    }

    protected function addStepForward()
    {
        if (!$this->showSteps) {
            return;
        }
        $page = $this->currentPage + 1;
        $this->pagesArr[] = [
            'title' => $this->languages[$this->lang][1][1],
            'link'  => $this->linkPatternStart . $page . $this->linkPatternEnd,
            'page'  => ''
        ];
    }

    protected function makeArray()
    {
        $max = ($this->maxItems < $this->lastPage ? $this->maxItems : $this->lastPage);
        // few pages
        if ($this->maxItems + 1 >= $this->lastPage) {
            $this->addToArr(1, $this->lastPage);
            return $this;
        }
        // on first page
        if ($this->currentPage == 1) {
            $this->addToArr(1, $max + 1);
            $this->addStepForward();
            $this->addLast();
            return $this;
        }
        // on last page
        if ($this->currentPage == $this->lastPage) {
            $start = $this->lastPage - $this->maxItems;
            $end = $this->lastPage;
            $this->addFirst();
            $this->addStepBack();
            $this->addToArr($start, $end);
            return $this;
        }
        $half = $this->maxItems / 2;
        // see first page
        if (($this->currentPage - $half) <= 1) {
            $end = $this->maxItems + 1;
            $this->addToArr(1, $end);
            $this->addStepForward();
            $this->addLast();
            return $this;
        }
        // see last page
        if (($this->currentPage + $half) >= $this->lastPage) {
            $start = $this->lastPage - $this->maxItems;
            $end = $this->lastPage;
            $this->addFirst();
            $this->addStepBack();
            $this->addToArr($start, $end);
            return $this;
        }
        //middle;
        $start = $this->currentPage - $half;
        $end = $this->currentPage + $half;
        $this->addFirst();
        $this->addStepBack();
        $this->addToArr($start, $end);
        $this->addStepForward();
        $this->addLast();
        return $this;
    }

}

