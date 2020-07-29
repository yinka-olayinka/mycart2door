<?php
    class Database
    {
        //Database credentials
        private $host = 'localhost';
        private $db_name = 'mycart2door';
        private $username = 'root';
        private $password = '';
        public $cn;
        
        //Initialize Connection
        function __construct()
        {
            $this->cn = null;
            $this->cn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        
        //Establish database connection
        public function getConnection()
        {
            return $this->cn;
        }
    }
?>