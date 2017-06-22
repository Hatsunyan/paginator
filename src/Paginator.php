<?php

/**
 * Created by PhpStorm.
 * User: Hatsunyan
 * Date: 17.03.2017
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
    protected $useUlWrapper      = true;
    protected $activePageClass   = 'active';
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
    public function __construct(int $items = 1, int $itemsOnPage = 1, int $currentPage = 1)
    {
        $this->setPages($items, $itemsOnPage, $currentPage);
        return $this;
    }

    /**
     * @param int $items - total items
     * @param int $itemsOnPage
     * @param int $currentPage
     * @return Paginator
     */
    public function setPages(int $items = 1, int $itemsOnPage = 1, int $currentPage = 1) : Paginator
    {
        $this->lastPage = ceil($items / $itemsOnPage);
        $this->setCurrentPage($currentPage);
        return $this;
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function setCurrentPage(int $page) : Paginator
    {
        $page = $page > $this->lastPage ? $this->lastPage : $page;
        $page = $page < 1 ? 1 : $page;
        $this->currentPage = $page;
        return $this;
    }

    /**
     * @param string $pattern like /news/{p}, where {p} - page number
     * @return Paginator
     */
    public function setUrlPattern(string $pattern) : Paginator
    {
        $array = explode('{p}', $pattern);
        $this->linkPatternStart = $array[0];
        $this->linkPatternEnd = $array[1];
        return $this;
    }

    /**
     * show next and prev buttons
     * @param bool $show
     * @return Paginator
     */
    public function showNextPrev(bool $show) : Paginator
    {
        $this->showSteps = $show;
        return $this;
    }

    /**
     * show last and first button
     * @param bool $show
     * @return Paginator
     */
    public function showLastFirst(bool $show) : Paginator
    {
        $this->showLastFirst = $show;
        return $this;
    }

    /**
     * out html     *
     */
    public function render() : void
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
    public function getHtml() : ?string
    {
        if ($this->html == null) {
            $this->makeHtml();
        }
        return $this->html;
    }

    /**
     * @param int $items
     * @return Paginator
     */
    public function setMaxItems(int $items) : Paginator
    {
        if($items % 2)
        {
            $items++;
        }
        $this->maxItems = $items;
        return $this;
    }

    /**
     * @param string $lang |ru|en|arrow
     * @return $this
     */
    public function setLang(string $lang)
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
     * @return Paginator
     */
    public function setCustomLang($fistLast, $nextPrev = []) : Paginator
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
        if(!empty($nextPrev))
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
     * @return Paginator
     */
    public function setUlClass(string $class) : Paginator
    {
        $this->ulClass = $class;
        return $this;
    }

    /**
     * @param bool $use
     * @return Paginator
     */
    public function useUlWrapper(bool $use) : Paginator
    {
        $this->useUlWrapper = $use;
        return $this;
    }

    /**
     * @return Paginator
     */
    public function refreshHtml() : Paginator
    {
        $this->html = null;
        return $this;
    }

    /**
     * @param string $class
     * @return Paginator
     */
    public function setActivePageClass(string $class) : Paginator
    {
        $this->activePageClass = $class;
        return $this;
    }

    /**
     * @return Paginator
     */
    protected function makeHtml() : Paginator
    {
        if ($this->lastPage == 1) {
            $this->html = null;
            return $this;
        }
        if(empty($this->pagesArr))
        {
            $this->makeArray();
        }
        if($this->useUlWrapper)
        {
            $this->html .= '<ul class="'.$this->ulClass.'">';
        }

        foreach ($this->pagesArr as $p)
        {
            if($this->useUlWrapper)
            {
                $this->html .= '<li>';
            }
            if ($p['page'] != $this->currentPage)
            {
                $this->html .= '<a href="' . $p['link'] . '">' . $p['title'] . '</a>';
            } else {
                $this->html .= '<a class="'.$this->activePageClass.'">' . $p['title'] . '</a>';
            }
            if($this->useUlWrapper)
            {
                $this->html .= '</li>';
            }
        }
        if($this->useUlWrapper)
        {
            $this->html .= '</ul>';
        }
        return $this;
    }

    protected function addToArr(int $start, int $end) : void
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

    protected function addLast() : void
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

    protected function addFirst() : void
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

    protected function addStepBack() : void
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

    protected function addStepForward() : void
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

    /**
     * @return Paginator
     */
    protected function makeArray() : Paginator
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