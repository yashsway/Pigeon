<?php
class reportBlueprint{
    //Client Defined
    public $ID;
    public $name;
    public $phone;
    public $email;
    public $department;
    public $request;
    public $custom_request;
    public $summary;
    public $details;
    public $priority;
    public $date;
    public $time;
    //Administrative Details
    public $duration = 2;
    public $admin_priority = "";
    public $admin_notes = "";
    public $markedForDeletion = false;

    public function __construct($id,$na,$ph,$em,$dep,$req,$cus,$summ,$det,$pri,$dat,$tim){
        $this->ID = $id;
        $this->name = $na;
        $this->phone = $ph;
        $this->email = $em;
        $this->department = $dep;
        $this->request = $req;
        $this->custom_request = $cus;
        $this->summary = $summ;
        $this->details = $det;
        $this->priority = $pri;
        $this->date = $dat;
        $this->time = $tim;
    }
}
?>
