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
    public $duration;
    public $admin_priority;
    public $admin_notes;
    public $markedForDeletion;

    public function __construct($id,$na,$ph,$em,$dep,$req,$cus,$summ,$det,$pri,$dat,$tim,$dur=1,$admPr="",$nte="",$del=0){
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
        $this->_date = $dat;
        $this->_time = $tim;
        $this->duration = $dur;
        $this->admin_priority = $admPr;
        $this->admin_notes = $nte;
        $this->markedForDeletion = $del;
    }
}
?>
