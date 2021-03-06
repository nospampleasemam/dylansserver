<?php

class page extends model {

  public $page = 1;
  public $offset = 0;
  public $notes_per_page = 4;
  public $number_of_pages = 1;
  public $notes = array();

  public function __construct() {
    parent::__construct();
    $this->page_offset();
    $this->fetch_notes();
  }

  private function page_offset() {
    $sql = "SELECT COUNT(*) FROM notes";
    $result = $this->db->query($sql);
    $result = $result->fetch_array();
    $this->number_of_pages = ceil($result[0] / $this->notes_per_page);
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
      $this->page = (int) $_GET['page'];
    } else {
      throw new notFound();
    }
    if ($this->page > $this->number_of_pages) {
      throw new notFound();
    }
    if ($this->page < 1) {
      throw new notFound();
    }
    $this->offset = ($this->page - 1) * $this->notes_per_page;
  }

  public function display() {
      require_once("view/page.php");
  }

  protected function fetch_notes() {
    $sql = "SELECT date_posted, title, url, text
              FROM notes ORDER BY date_posted DESC
              LIMIT ?, ?";
    $result = $this->query($sql, "ii",
                              $this->offset,
                              $this->notes_per_page);
    foreach ($result as $row => $entry) {
      $entry['url'] = '/note/' . $entry['url'];
      $date_posted = explode("-", $entry['date_posted']);
      $entry['year_posted'] = $date_posted[0];
      $entry['month_posted'] = $date_posted[1];
      $entry['datetime_posted'] = explode(' ', $date_posted[2]);
      $entry['day_posted'] = $entry['datetime_posted'][0];
      $this->notes[$row] = $entry;
    }
  }
}

?>
