<?php
$servername = "localhost"; // Or your DB server
$username = "root";        // Your DB username
$password = "";            // Your DB password
$dbname = "db_gct";        // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function get_reg_details($reg_number){
	//decode the Degree/Department/FT-PT from the registration number
	$reg=$reg_number;
	$data=array();
	$data['degree']='';
	$data['department']='';
	$data['time']='';
	//year
	$year=(int)substr($reg,0,2);
	if($year < 9 || $year > 50){
		$data['year']='19'.substr($reg,0,2);
	}else{
		$data['year']='20'.substr($reg,0,2);
	}
	//department
	$third_digit=(int)substr($reg,2,1);
	if($third_digit<3){
		$number=substr($reg,2,2);
	}else{
		$number=substr($reg,6,3);
	}
	
    $query = \Drupal::database()->select('reg_format', 'rf');
    $query->fields('rf', ['degree', 'department', 'time_type']);
    $query->condition('rf.number', $number, '=');

    // Execute the query and fetch the result
    $result = $query->execute();
    $row = $result->fetchAssoc();

    // Check if a row is returned and populate $data array
    if ($row) {
        $data['degree'] = $row['degree'];
        $data['department'] = $row['department'];
        $data['time'] = $row['time_type'];
    }

    return $data;
}
?>
<style type="text/css">.reg_table {
width:100%;
}
.reg_table table tr{
	background-color:#FFFFFF;
	border:none;
}
.result_tbl{
width:100%;
}

.result_error{
width:100%;
text-align:center;
color:#FF5959;
margin-bottom:20px;
}
</style>
<p>&nbsp;</p>

<div id="divToPrint">
<form action="" class="result_frm" method="post">
		<div align="center" class="reg_table">
			<table>
				<tbody>
					<tr>
						<td>
							<h4>Enter a registration number and click the 'Submit' button:</h4>
						</td>
						<td>
							<input class="form-text" maxlength="128" name="reg_no" size="20" type="text" value="" />
						</td>
						<td>
							<input class="btn" name="btn_submit" style="margin-top:-5px" type="submit" value="Submit" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>


	<?php
	// Check if the form is submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Handle "Clear All" button
		if (isset($_POST['btn_clear'])) {
			// Clear all values from the 'result_submissions' table
			$database = \Drupal::database();
		}
		// Handle "Submit" button
		elseif (isset($_POST['btn_submit'])) {
			$reg_no = $_POST['reg_no'];

			// Get the current date and time
			$current_date_time = \Drupal::time()->getCurrentTime();

			// Get the user's IP address
			$ip_address = \Drupal::request()->getClientIp();

			// Determine the device type based on User-Agent
			$deviceType = 'Desktop'; // Default to Desktop
			$u_agent = $_SERVER['HTTP_USER_AGENT'];

			// Check for Mobile
			if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $u_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($u_agent, 0, 4))) {
				$deviceType = 'Mobile';
			}

			if (stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5.0(iPad;')) {
				$deviceType = 'Tablet';
			}

			// Insert data into the 'result_submissions' table using Drupal's Database API
			$database = \Drupal::database();

			// Define fields to insert
			$fields = [
				'roll_number' => $reg_no,
				'submission_date_time' => date('Y-m-d H:i:s', $current_date_time),
				'ip_address' => $ip_address,
				'device_type' => $deviceType,
			];

			// Insert only if 'user_agent' field exists in the table
			if ($database->schema()->fieldExists('result_submissions', 'user_agent')) {
				$fields['user_agent'] = $u_agent;
			}

			// Perform the insert operation
			$result = $database->insert('result_submissions')
				->fields($fields)
				->execute();
		}
	}

// Query the database for submission counts grouped by date
	$query = "SELECT DATE(submission_date_time) AS submission_date, COUNT(*) AS submission_count
          FROM result_submissions
          GROUP BY DATE(submission_date_time)
          ORDER BY submission_date";

// Execute query
$result = $conn->query($query);

// Check if results exist
if ($result->num_rows > 0) {
    // Display results in a table
    echo '<div class="submission_counts" style="text-align: center; margin-top: 20px;">';
    echo '<h4>Submission Counts by Date</h4>';
    echo '<table border="1" style="margin: 0 auto; border-collapse: collapse;">';
    echo '<tr><th>Date</th><th>Count</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['submission_date'] . '</td>';
        echo '<td>' . $row['submission_count'] . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
} else {
    echo "No data found.";
}


	?>



<?php
$msg='';
if(isset($_POST['reg_no'])){
	
	if(trim($_POST['reg_no'])!=''){
	
		$reg_no = trim($_POST['reg_no']);

// Insert into result_loggger
\Drupal::database()->insert('result_loggger')
    ->fields(['roll_number' => $reg_no])
    ->execute();

$sem = false;

// Select from mark_details
$query = \Drupal::database()->select('mark_details', 'SM');
$query->leftJoin('student_cgpa', 'S', 'S.roll_number = SM.roll_number');
$query->fields('SM', ['roll_number', 'student_name', 'sem_num', 'subject_code', 'credit', 'lg', 'gp', 'p_f', 'comment']);
$query->fields('S', ['cgpa']);
$query->condition('SM.roll_number', $reg_no, '=');
$result = $query->execute();
$marks = $result->fetchAll(PDO::FETCH_ASSOC);

// Check if there are any rows returned
if (!empty($marks)) {
    $sem = true;
} else {
    // Select from unit_mark_details if no results from mark_details
    $query = \Drupal::database()->select('unit_mark_details', 'UM');
    $query->fields('UM', ['roll_number', 'student_name', 'sem_num', 'subject_code', 'unit_1', 'ass_1', 'unit_2', 'ass_2', 'unit_3', 'ass_3', 'att_1', 'att_2', 'att_3', 'cal_1', 'cal_2']);
    $query->condition('roll_number', $reg_no, '=');
    $result = $query->execute();
    $marks = $result->fetchAll(PDO::FETCH_ASSOC);

    if (empty($marks)) {
        $msg = 'Registration number is not found.';
    }
}

// Further processing with $marks or handling $msg if necessary

		
if(!$msg){
	$is_phd=strlen($reg_no)>7?true:false;
	if(!$is_phd){
		$details=get_reg_details($reg_no);
		$year=(int)$details['year'];
	}else{
		$year=2010;
	}
?>

<div align="center" class="result_tbl">
<table border="0" cellpadding="0" cellspacing="0" class="tbl_logo" style="display:none; background:#002060;">
	<tbody>
    	<td><img src="https://gct.ac.in/sites/gct.ac.in/files/logo_0_0.png"></td>
	</tbody>
</table>

<table cellpadding="4">
	<tbody>
		<tr>
			<td><strong>Registration Number</strong></td>
			<td><?php echo $marks[0]['roll_number']; ?></td>
			<td><strong>Name</strong></td>
			<td><?php echo $marks[0]['student_name']?$marks[0]['student_name']:'-'; ?></td>
		</tr>
		<tr>
			<!-- <td><strong>Degree</strong></td> -->
			<?php 
				if($details['degree'] == ""){
					$details['degree'] = "M.E ";
				}
				if($details['time'] == ""){
					$details['time'] = "Full Time";
				}	
			?>
			<!-- <td><?php echo  $details['degree'].'-'.$details['time']; ?></td> -->
			<?php if($is_phd){ ?>
			<td ><strong>Assessment/Semester Month </strong></td>
			<!--<td><?php echo $marks[0]['sem_num']?$marks[0]['sem_num']:'-'; ?></td>-->
            <td>NOV/DEC 2024 END SEMESTER EXAMINATION RESULTS (Phase I)</td>
            <td></td>
            <td></td>
			<?php }else{ ?>
			<td><strong>Department Name</strong></td>
			<td><?php echo $details['department']?$details['department']:'-'; ?></td>
			<?php } ?>
		</tr>
		<?php if(!$is_phd){ ?>
		<tr>
			<td><strong>Assessment/Semester Month </strong></td>
			<!--<td><?php echo $marks[0]['sem_num']?$marks[0]['sem_num']:'-'; ?></td>-->
                       <td>NOV/DEC 2024 END SEMESTER EXAMINATION RESULTS (Phase I)</td>

			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php	
	if($sem==true){ //sem marks
	if($marks[0]['comment']){
		$msg=$marks[0]['comment'];
	}else{
?>

<table cellpadding="4">
	<thead>
		<tr>
			<td><strong>Subject Code</strong></td>
			<td><strong><?php echo ( $year<2009 )?'Internal Marks':'Credit'; ?></strong></td>
			<td><strong><?php echo ( $year<2009 )?'External Marks':'Letter Grade'; ?></strong></td>
			<td><strong><?php echo ( $year<2009 )?'Total Marks':'Grade Point'; ?></strong></td>
			<td><strong>Result</strong></td>
		</tr>
	</thead>
	<tbody><?php
		$gpa = 0;
        foreach($marks as $mark){
			// //CGPA Calculation
   //      	//echo '<pre>';print_r($marks);exit;
   //      	//commented by satheesh as per gct request on 26.6.2019
			// $cp[] = $mark['credit'];
			// $cpgp[] = $mark['credit']*$mark['gp'];
			// $gp[] = $mark['gp'];
			
			// $tcpgp = array_sum($cpgp);
			// $tcp = array_sum($cp); 
			// $tgp = array_sum($gp);
			// $gpa = number_format($tcpgp/$tcp,2);

			//commented by satheesh as per the process on 12.4.2022
			$gpa = $mark['cgpa'];
			
			if($mark['p_f']){ ?>
		<tr>
			<td>
			<div align="center"><?php echo $mark['subject_code']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['credit']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['lg']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['gp']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['p_f']; ?></div>
			</td>
		</tr>
		<?php
			} 
		}
        ?>
	</tbody>
</table>
<?php //if(isset($gpa)) {//if(isset($marks[0]['cgpa'])){  ?>
<?php 
	//$new_reg_no = substr($reg_no, 0, 2);
	//if($new_reg_no > '09' && $new_reg_no < '50') {
if(isset($gpa) && $gpa > 0){
?>
<table>
	<tbody>
		<tr>
			<td><strong><?php echo ($year >= 2009)?'GPA':'Total'; ?></strong></td>
			<td><?php echo $gpa; ?></td>
		</tr>
	</tbody>
</table>
<?php } ?>
<?php // } ?>

</div>

<div align="center"><a class="print" href="javascript:" onclick="PrintDiv()"><strong>Print</strong></a></div>
<?php }
 }
else{//unit marks
?>

<?php foreach($marks as $mark){	 ?>

<table>
	<thead>
		<tr>
			<td>
			<div align="center"><strong><?php echo $mark['subject_code']; ?></strong></div>
			</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			<table>
				<tbody>
					<tr>
						<td>
						<div align="center"><strong>Unit1</strong></div>
						</td>
						<td>
						<div align="center"><strong>Unit2</strong></div>
						</td>
						<td>
						<div align="center"><strong>Unit3</strong></div>
						</td>
						<td><strong>Attendance</strong></td>
						<td><strong>Session Marks</strong></td>
					</tr>
					<tr>
						<td>
						<table>
							<tbody>
								<tr>
									<td>Marks (50)</td>
									<td>Assignment (10)</td>
									<td>Attendance %</td>
								</tr>
								<tr>
									<td>
									<div align="center"><?php echo $mark['unit_1']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['ass_1']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['att_1']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
						<td>
						<table>
							<tbody>
								<tr>
									<td>Marks (50)</td>
									<td>Assignment (10)</td>
									<td>Attendance %</td>
								</tr>
								<tr>
									<td>
									<div align="center"><?php echo $mark['unit_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['ass_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['att_2']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
						<td>
						<table>
							<tbody>
								<tr>
									<td>Marks (50)</td>
									<td>Assignment (10)</td>
									<td>Attendance %</td>
								</tr>
								<tr>
									<td>
									<div align="center"><?php echo $mark['unit_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['ass_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['att_3']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
						<td>
						<div align="center"><?php echo $mark['cal_1']; ?></div>
						</td>
						<td>
						<div align="center"><?php echo $mark['cal_2']; ?></div>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
<?php }  ?></div><?php

function get_reg_details($reg_number){
	//decode the Degree/Department/FT-PT from the registration number
	$reg=$reg_number;
	$data=array();
	$data['degree']='';
	$data['department']='';
	$data['time']='';
	//year
	$year=(int)substr($reg,0,2);
	if($year < 9 || $year > 50){
		$data['year']='19'.substr($reg,0,2);
	}else{
		$data['year']='20'.substr($reg,0,2);
	}
	//department
	$third_digit=(int)substr($reg,2,1);
	if($third_digit<3){
		$number=substr($reg,2,2);
	}else{
		$number=substr($reg,6,3);
	}
	
$query = \Drupal::database()->select('reg_format', 'rf');
$query->fields('rf', ['degree', 'department', 'time_type']);
$query->condition('number', $number, '=');
$result = $query->execute();

if ($result->rowCount()) {
    $row = $result->fetchAssoc();
    $data['degree'] = $row['degree'];
    $data['department'] = $row['department'];
    $data['time'] = $row['time_type'];
}
	return $data;
}
?>
<style type="text/css">.reg_table {
width:100%;
}
.reg_table table tr{
	background-color:#FFFFFF;
	border:none;
}
.result_tbl{
width:100%;
}

.result_error{
width:100%;
text-align:center;
color:#FF5959;
margin-bottom:20px;
}
</style>
<p>&nbsp;</p>

<div id="divToPrint">
<form action="" class="result_frm" method="post">
<div align="center" class="reg_table">
<table>
	<tbody>
		<tr>
			<td>
			<h4>Enter a&nbsp;registration number and click the 'Submit' button&nbsp;:</h4>
			</td>
			<td><input class="form-text" maxlength="128" name="reg_no" size="20" type="text" value="" /></td>
			<td><input class="btn" name="btn" style="margin-top:-5px" type="submit" value="Submit" /></td>
		</tr>
	</tbody>
</table>
</div>
</form>
<?php
$msg='';
if(isset($_POST['reg_no'])){
	
	if(trim($_POST['reg_no'])!=''){
	
		$reg_no=trim($_POST['reg_no']);
	
		//result logger
		\Drupal::database()->insert('result_loggger')
		->fields(array(
		'roll_number' => $reg_no
		))
		->execute();
		
		
		$sem=false;
		
		$query = \Drupal::database()->select('mark_details', 'SM');
		$query->leftJoin('student_cgpa', 'S', 'S.roll_number = SM.roll_number'); 
		$query->fields('SM',array('roll_number','student_name','sem_num','subject_code','credit','lg','gp','p_f','comment'))
		->fields('S',array('roll_number','cgpa'));
		$query->condition('SM.roll_number',$reg_no,'=');
		$result = $query->execute();
		echo $result;
		if($result->rowCount()){
			$marks=$result->fetchAll(PDO::FETCH_ASSOC);
			$sem=true;
		}else{
			$result = \Drupal::database()->select('unit_mark_details', 'UM')
			->fields('UM',array('roll_number','student_name','sem_num','subject_code','unit_1','ass_1','unit_2','ass_2','unit_3','ass_3','att_1','att_2','att_3','cal_1','cal_2'))
			->condition('roll_number',$reg_no,'=')
			->execute();
			
			if($result->rowCount()){
				$marks=$result->fetchAll(PDO::FETCH_ASSOC);
			}else{
				$msg='Registration number is not found.';
			}
		}
		
if(!$msg){
	$is_phd=strlen($reg_no)>17?true:false;
	if(!$is_phd){
		$details=get_reg_details($reg_no);
		$year=(int)$details['year'];
	}else{
		$year=2010;
	}
?>

<div align="center" class="result_tbl">
<table border="0" cellpadding="0" cellspacing="0" class="tbl_logo" style="display:none; background:#002060;">
	<tbody>
    	<td><img src="https://gct.ac.in/sites/gct.ac.in/files/logo_0_0.png"></td>
	</tbody>
</table>

<table cellpadding="4">
	<tbody>
		<tr>
			<td><strong>Registration Number</strong></td>
			<td><?php echo $marks[0]['roll_number']; ?></td>
			<td><strong>Name</strong></td>
			<td><?php echo $marks[0]['student_name']?$marks[0]['student_name']:'-'; ?></td>
		</tr>
		<tr>
			<!-- <td><strong>Degree</strong></td> -->
			<!-- <td><?php echo $details['degree'].'-'.$details['time']; ?></td> -->
			<?php if($is_phd){ ?>
			<td><strong>Assessment/Semester Month </strong></td>
			<!--<td><?php echo $marks[0]['sem_num']?$marks[0]['sem_num']:'-'; ?></td>-->
                        <td>NOV/DEC 2024 END SEMESTER EXAMINATION RESULTS (Phase I)</td>
                        <td></td>
            <td></td>
			<?php }else{ ?>
			<td><strong>Department Name</strong></td>
			<td><?php echo $details['department']?$details['department']:'-'; ?></td>
			<?php } ?>
		</tr>
		<?php if(!$is_phd){ ?>
		<tr>
			<td><strong>Assessment/Semester Month </strong></td>
			<!--<td><?php echo $marks[0]['sem_num']?$marks[0]['sem_num']:'-'; ?></td>-->
                       <td>NOV/DEC 2024 END SEMESTER EXAMINATION RESULTS (Phase I)</td>

			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php	
	if($sem==true){ //sem marks
	if($marks[0]['comment']){
		$msg=$marks[0]['comment'];
	}else{
?>

<table cellpadding="4">
	<thead>
		<tr>
			<td><strong>Subject Code</strong></td>
			<td><strong><?php echo ( $year<2009 )?'Internal Marks':'Credit'; ?></strong></td>
			<td><strong><?php echo ( $year<2009 )?'External Marks':'Letter Grade'; ?></strong></td>
			<td><strong><?php echo ( $year<2009 )?'Total Marks':'Grade Point'; ?></strong></td>
			<td><strong>Result</strong></td>
		</tr>
	</thead>
	<tbody><?php
		$gpa = 0;
        foreach($marks as $mark){
        //	echo"<pre>";print_r($mark);exit;
			//CGPA Calculation

        	//commented by satheesh as per gct request on 26.6.2019
			// $cp[] = $mark['credit'];
			// $cpgp[] = $mark['credit']*$mark['gp'];
			// $gp[] = $mark['gp'];
			
			// $tcpgp = array_sum($cpgp);
			// $tcp = array_sum($cp); 
			// $tgp = array_sum($gp);
			// $gpa = $tcpgp/$tcp;

			$gpa = $mark['cgpa'];
			
			if($mark['p_f']){?>
		<tr>
			<td>
			<div align="center"><?php echo $mark['subject_code']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['credit']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['lg']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['gp']; ?></div>
			</td>
			<td>
			<div align="center"><?php echo $mark['p_f']; ?></div>
			</td>
		</tr>
		<?php
			} 
		}
        ?>
	</tbody>
</table>
<?php if(isset($gpa)) {//if(isset($marks[0]['cgpa'])){  ?>
<?php 
	$new_reg_no = substr($reg_no, 0, 2);
	if($new_reg_no > '09' && $new_reg_no < '50') {
?>
<table>
	<tbody>
		<tr>
			<td><strong><?php echo ($year >= 2009)?'GPA':'Total'; ?></strong></td>
			<td><?php echo $gpa; ?></td>
		</tr>
	</tbody>
</table>
<?php } ?>
<?php  } ?></div>

<div align="center"><a class="print" href="javascript:" onclick="PrintDiv()"><strong>Printssss</strong></a></div>
<?php }
 }
else{//unit marks
?>

<?php foreach($marks as $mark){	 ?>

<table>
	<thead>
		<tr>
			<td>
			<div align="center"><strong><?php echo $mark['subject_code']; ?></strong></div>
			</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			<table>
				<tbody>
					<tr>
						<td>
						<div align="center"><strong>Unit1</strong></div>
						</td>
						<td>
						<div align="center"><strong>Unit2</strong></div>
						</td>
						<td>
						<div align="center"><strong>Unit3</strong></div>
						</td>
						<td><strong>Attendance</strong></td>
						<td><strong>Session Marks</strong></td>
					</tr>
					<tr>
						<td>
						<table>
							<tbody>
								<tr>
									<td>Marks (50)</td>
									<td>Assignment (10)</td>
									<td>Attendance %</td>
								</tr>
								<tr>
									<td>
									<div align="center"><?php echo $mark['unit_1']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['ass_1']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['att_1']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
						<td>
						<table>
							<tbody>
								<tr>
									<td>Marks (50)</td>
									<td>Assignment (10)</td>
									<td>Attendance %</td>
								</tr>
								<tr>
									<td>
									<div align="center"><?php echo $mark['unit_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['ass_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['att_2']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
						<td>
						<table>
							<tbody>
								<tr>
									<td>Marks (50)</td>
									<td>Assignment (10)</td>
									<td>Attendance %</td>
								</tr>
								<tr>
									<td>
									<div align="center"><?php echo $mark['unit_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['ass_2']; ?></div>
									</td>
									<td>
									<div align="center"><?php echo $mark['att_3']; ?></div>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
						<td>
						<div align="center"><?php echo $mark['cal_1']; ?></div>
						</td>
						<td>
						<div align="center"><?php echo $mark['cal_2']; ?></div>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
<?php }  ?></div>
<?php
	}
	}
else{
	$msg='
	No Results found<br>
	
	"OOPS!!! No Results found, Possible reasons are: Results are yet to be published OR You might have entered a wrong registration number OR the current release of results may NOT include your batch. Concerns, Please contact the COE office Immediately."';
}
	}
	else{
	$msg='Please enter a registration number.';
	}
}else{
	//$msg='Please enter a registration number.';
}

//error message
 if($msg){
	 echo '<div class="result_error">'.$msg.'</div>';
 }
?>
<script type="text/javascript">
	function PrintDiv() {
			jQuery(".result_frm").prev("table").css("margin-bottom" , "15px");
			jQuery(".result_frm").hide();
			jQuery(".tbl_logo").show();
			//jQuery(".tbl_logo").children('tbody').children('td').html('<img src="https://gct.ac.in/sites/gct.ac.in/files/logo_0_0.png">');
			//jQuery(".print").hide();
			
	
		var divToPrint = document.getElementById('divToPrint');
		var popupWin = window.open('', '_blank', 'width=800,height=600');
		popupWin.document.open();
		popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
		popupWin.document.close();
		jQuery(".result_frm").show();
		
		
	}
</script>
<?php
	}
	}
else{
	$msg='
	No Results found<br>
	
	"OOPS!!! No Results found, Possible reasons are: Results are yet to be published OR You might have entered a wrong registration number OR the current release of results may NOT include your batch. Concerns, Please contact the COE office Immediately."';
}
	}
	else{
	$msg='Please enter a registration number.';
	}
}else{
	//$msg='Please enter a registration number.';
}

//error message
 if($msg){
	 echo '<div class="result_error">'.$msg.'</div>';
 }
?>
<script type="text/javascript">
	function PrintDiv() {
			jQuery(".result_frm").prev("table").css("margin-bottom" , "15px");
			jQuery(".result_frm").hide();
			jQuery(".tbl_logo").show();
			//jQuery(".tbl_logo").children('tbody').children('td').html('<img src="https://gct.ac.in/sites/gct.ac.in/files/logo_0_0.png">');
			//jQuery(".print").hide();
			
	
		var divToPrint = document.getElementById('divToPrint');
		var popupWin = window.open('', '_blank', 'width=800,height=600');
		popupWin.document.open();
		popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
		popupWin.document.close();
		jQuery(".result_frm").show();
		
		
	}
</script>