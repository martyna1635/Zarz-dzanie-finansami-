<?php
class Database {

  protected $host;
  protected $user;
  protected $pwd;
  protected $dbName;
  protected $dbLink;
  protected $result;
  protected $resultObj;

// Ustawienia do połączenia
  function __construct(){
    $this->host = '10.254.94.2';
    $this->user = 's173529';
    $this->pwd = 'Parkowa3';
    $this->dbName = 's173529';
    
    $this->connect();
    }

  // Połącz się z serwerem mySQL i wybierz bazę danych 
  public function connect() {
    try {
      $this->dbLink = @mysqli_connect($this->host, $this->user, $this->pwd, $this->dbName);
      if (!$this->dbLink) {
        throw new Exception ("Nie można było połączyć użytkownika $this->user z bazą $this->dbName");
        }
      }
    catch (Exception $e) {
      echo $e->getMessage();
      exit();
      }
    return $this->dbLink;
    }

 // Wykonaj zapytanie SQL 
  public function query($query) {
    try {
      $this->result = mysqli_query($this->dbLink, $query);
      if (!$this->result) {
        throw new Exception ('Błąd MySQL: ' . mysqli_error($this->dbLink));
        }
      }
    catch (Exception $e) {
      echo $e->getMessage();
      exit();
      }
        // zapisz wynik do nowego obiektu, który będzie imitować interfejs mysqli OO 
        $this->resultObj = new MyResult($this->result);
        return $this->resultObj;
    }

   // zamknij połączenie MySQL
  public function close(){
    mysqli_close($this->dbLink);
    }   

  // Zwaraca połaczenie do bazy danych (w celu bardziej skompikowanych operacji)
    public function getLink(){
      return $this->dbLink;
    }
  }

class MyResult {

  public $theResult;
  public $num_rows;
  
  function __construct($r) {
      if (is_bool($r)) {
            $this->num_rows = 0;
                }
          else {
            $this->theResult = $r;
            // pobierz całkowitą liczbę znalezionych rekordów
            $this->num_rows = mysqli_num_rows($r);
                }
        }
  
  // wyswietla wynik w formie tablicy
  function getResult() {
      $rows = array();
      while ($row = mysqli_fetch_assoc($this->theResult)) {
          $rows[] = $row;
      }
      return $rows;
    }
  }
?>