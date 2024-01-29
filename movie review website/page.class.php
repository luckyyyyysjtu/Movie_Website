<?php
 
/* * *********************************************
 * @Class name:   page
 * @param:  $myde_total - Total Records
 *          $myde_size - 一Number of records displayed on the page
 *          $myde_page - current page
 *          $myde_url - Get the current URL
 */
 
class page {
 
    private $myde_total;          //Total Records
    private $myde_size;           //Number of records displayed on the page
    private $myde_page;           //current page
    private $myde_page_count;     //PageCount
    private $myde_i;              //Number of starting pages
    private $myde_en;             //Number of ending pages
    private $myde_url;            //Get the current URL
    /*
     * $show_pages
     * The format of page display, with 2 pages displaying links*$show_pages+1。
     * example:$show_pages=2 So the display on the page is [Home] [Previous] 1 2 3 4 5 [Next] [Last]
     */
    private $show_pages;
 
    public function __construct($myde_total = 1, $myde_size = 1, $myde_page = 1, $myde_url, $show_pages = 2) {
        $this->myde_total = $this->numeric($myde_total);
        $this->myde_size = $this->numeric($myde_size);
        $this->myde_page = $this->numeric($myde_page);
        $this->myde_page_count = ceil($this->myde_total / $this->myde_size);
        $this->myde_url = $myde_url;
        if ($this->myde_total < 0)
            $this->myde_total = 0;
        if ($this->myde_page < 1)
            $this->myde_page = 1;
        if ($this->myde_page_count < 1)
            $this->myde_page_count = 1;
        if ($this->myde_page > $this->myde_page_count)
            $this->myde_page = $this->myde_page_count;
        $this->limit = ($this->myde_page - 1) * $this->myde_size;
        $this->myde_i = $this->myde_page - $show_pages;
        $this->myde_en = $this->myde_page + $show_pages;
        if ($this->myde_i < 1) {
            $this->myde_en = $this->myde_en + (1 - $this->myde_i);
            $this->myde_i = 1;
        }
        if ($this->myde_en > $this->myde_page_count) {
            $this->myde_i = $this->myde_i - ($this->myde_en - $this->myde_page_count);
            $this->myde_en = $this->myde_page_count;
        }
        if ($this->myde_i < 1)
            $this->myde_i = 1;
    }
 
    //Detect whether it is a number
    private function numeric($num) {
        if (strlen($num)) {
            if (!preg_match("/^[0-9]+$/", $num)) {
                $num = 1;
            } else {
                $num = substr($num, 0, 11);
            }
        } else {
            $num = 1;
        }
        return $num;
    }
 
    //Address Replacement
    private function page_replace($page) {
        return str_replace("{page}", $page, $this->myde_url);
    }
 
    //home page
    private function myde_home() {
        if ($this->myde_page != 1) {
            return "<li><a href='" . $this->page_replace(1) . "' title='First Page'>First Page</a></li>";
        } else {
            return "<li><span>First Page</span></li>";
        }
    }
 
    //previous page
    private function myde_prev() {
        if ($this->myde_page != 1) {
            return "<li><a href='" . $this->page_replace($this->myde_page - 1) . "'>&laquo;</a></li>";
        } else {
            return "<li class='disabled'><a href='##'>&laquo;</a></li>";
        }
    }
 
    //next page
    private function myde_next() {
        if ($this->myde_page != $this->myde_page_count) {
            return "<li><a href='" . $this->page_replace($this->myde_page + 1) . "'>&raquo;</a></li>";
        } else {
            return "<li class='disabled'><a href='##'>&raquo;</a></li>";
        }
    }
 
    //Last Page
    private function myde_last() {
        if ($this->myde_page != $this->myde_page_count) {
            return "<li><a href='" . $this->page_replace($this->myde_page_count) . "' title='Last Page'>Last Page</a></li>";
        } else {
            return "<li><span>Last Page</span></li>";
        }
    }
 
    //output
    public function myde_write() {
        $str = '<ul class="pagination">';
        // $str.=$this->myde_home();
        $str.=$this->myde_prev();
        
        for ($i = $this->myde_i; $i <= $this->myde_en; $i++) {
            if ($i == $this->myde_page) {
                $str.='<li class="active"><a href="##">'.$i.'</a></li>';
            } else {
                $str.='<li><a href="' . $this->page_replace($i) .'">'.$i.'</a></li>';
            }
        }

        $str.=$this->myde_next();
        // $str.=$this->myde_last();
        // $str.='<li><a href="##">Total:' . $this->myde_total . '</li>';
        $str.="</ul>";
        return $str;
    }
 
}
 
?>