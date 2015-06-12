<?php


class Db{
	public $_con; //public class variable

	
public function __construct(){
	$this->connect();
}	


public function connect(){
	$_mode = 0;
	if($_mode == 0){
		$this->_con = new mysqli("127.0.0.1", "root", "", "llc_local_test"); //dev connection
	}else{
		$this->_con = new mysqli("130.113.143.45:3306", "psousa", "B3asl3y", "hcs-res-survey");	//prod connection - do not use
	}
	
}

public function get_all_records_for_table(){
	$query = $this->_con->prepare('select students.id as s_id, llc2015.id as l_id, students.studentNo, students.studentID, students.studentLastName, students.studentFirstName, llc2015.q1, llc2015.q2, llc2015.state from llc2015 left join students on students.id = llc2015.f_id where students.id IS NOT NULL');
	
	//$query->bind_param("ss", $building, $floor);
	$query->execute();
	$result = $query->get_result();
	$json_result=array();

/*
	while($row = $result->fetch_assoc()){
		$json_result[] = $row;
	
}
*/
// 	return $json_result;

	return $result;
}

public function ajax_get_single_student($id){
	$query_1 = $this->_con->prepare('select students.id, students.studentNo, students.studentID, students.studentFirstName, students.studentLastName, llc2015.q1, llc2015.q2, llc2015.q3, llc2015.q4, llc2015.q5, llc2015.q6, llc2015.depositreceived, llc2015.datesubmitted, llc2015.state as status from llc2015 left join students on students.id = llc2015.f_id where students.id IS NOT NULL and llc2015.id = ?');
	
	$query_1->bind_param("i", $id);
	$query_1->execute();
	$result = $query_1->get_result();
	
	$json_result = array();
	
	while($row = $result->fetch_assoc()){
		$json_result[] = $row;
	}
	
	return $json_result;
	
	
}


public function get_count_of_llc($llc){
	//This will grab the counts of the specific llc and then the amount that is in approval.  return array with three values.
	
	$query_1 = $this->_con->prepare('select llc2015.q1 from llc2015 left join students on students.id = llc2015.f_id where students.id IS NOT NULL and llc2015.q2 = ?');
	$query_2 = $this->_con->prepare('select llc2015.state from llc2015 left join students on students.id = llc2015.f_id where students.id IS NOT NULL and llc2015.state = 0 and llc2015.q2 = ?');	
	
	$query_1->bind_param("i", $llc);
	$query_1->execute();
	$query_1->store_result();
	
	$query_2->bind_param("i", $llc);	
	$query_2->execute();
	$query_2->store_result();
	
	$result = array();
	
	$result[0] = $query_1->num_rows;//total for community
	$result[1] = $query_2->num_rows;//total for unnaproved
	$result[2] = $result[0] - $result[1];//total for approved
	return $result;
}

public function ajax_change_state($id, $state){
	if ($state == 0){
		$change_state = 1;
	}else{
		$change_state = 0;
	}
	$query_1 = $this->_con->prepare('UPDATE llc2015 set state = ? where id = ?');
	$query_1->bind_param("ii", $change_state, $id);
	$query_1->execute();
	
	//if($query_1){
		return array("result"=> true);
	//}else{
	//	return array("result"=> false);
	//}
}
	
	
	
	
	
	
	
	
	
	
	
}

?>