<?php

class MedicineScheduler
{

    private $conn;

    private $terms;

    public function __construct()
    {
        // configuration
        $dbhost 	= DB_HOST;
        $dbname		= DB_DB;

        // database connection
        $this->conn = new PDO("mysql:host=$dbhost;dbname=$dbname",DB_USER,DB_PASS);

        $this->terms = array();

        $this->terms[] = array(
            'term' => 'Rice Milk',
            'type' => 'food',
            'note' => '5 oz',
            'hours'=> '8' // hours from midnight
        );

        $this->terms[] = array(
            'term' => 'neocate jr. prebiotics vanilla',
            'type' => 'medicine',
            'note' => '5 oz',
            'hours'=> '8' // hours from midnight
        );

        $this->terms[] = array(
            'term' => 'Rice Milk',
            'type' => 'food',
            'note' => '5 oz',
            'hours'=> '14' // hours from midnight
        );

        $this->terms[] = array(
            'term' => 'neocate jr. prebiotics vanilla',
            'type' => 'medicine',
            'note' => '5 oz',
            'hours'=> '14' // hours from midnight
        );

        $this->terms[] = array(
            'term' => 'Prevacid',
            'type' => 'medicine',
            'note' => '5 ml',
            'hours'=> '7' // hours from midnight
        );

        $this->terms[] = array(
            'term' => 'Zyrtec',
            'type' => 'medicine',
            'note' => '2.5 ml',
            'hours'=> '20' // hours from midnight
        );

        $this->terms[] = array(
            'term' => 'Fluticasone',
            'type' => 'medicine',
            'note' => '50 MCG AE',
            'hours'=> '20' // hours from midnight
        );
    }

    public function Run($date)
    {
        foreach($this->terms as $t)
        {
            $d = new DateTime($date);
            $d = $d->add(new DateInterval("PT" . $t['hours'] . "H"));
            $this->Add($t['type'], $t['term'], $t['note'], $d);
        }
    }


    private function Add($type, $term, $note, DateTime $datetime)
    {
        $type = ucfirst($type);

        $sql = "SELECT {$type}TypeId FROM {$type}Type WHERE {$type}Name = :term";
        $q = $this->conn->prepare($sql);
        $q->execute(array(':term'=>$term));

        $result = $q->fetch(PDO::FETCH_ASSOC);
        $id = $result[$type . 'TypeId'];

        if ($id)
        {
            $sql = "INSERT INTO {$type} ({$type}TypeId,{$type}Date, {$type}Note ) VALUES (:id, :now,:note)";
            //echo "\n ************** \n Id: $id \n Term: $term \n Note: $note \n Date: " . $datetime->format("Y-m-d H:i:s");

            $q = $this->conn->prepare($sql);
            $q->execute(array(':id'=>$id, ':now'=>$datetime->format("Y-m-d H:i:s"), ':note'=>$note));

        }
    }
}