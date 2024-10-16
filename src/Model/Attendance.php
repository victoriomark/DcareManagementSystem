<?phpnamespace Model;use Controller\attendanceConroller;include_once '../../Config/DbConnection.php';class Attendance extends \DbConnection{    public $status = 'Enrolled';   public function DisplayStudentList($schedule)   {       $query = 'select * from student where Status = ? and schedule  = ?';       $stmt = $this->Connect()->prepare($query);       $stmt->bind_param('ss',$this->status,$schedule);       $stmt->execute();       $result = $stmt->get_result();      $student = [];      while ($row = $result->fetch_assoc()){          $student[] = $row;      }     return $student;   }    // Function to insert attendance    public function InsertAttendance($studentId, $studentName,$status)    {        $date = date('Y-m-d'); // Use the current date for attendance        $sql = "INSERT INTO attendance (StudentNo, StudentName, Date,status) VALUES (?, ?, ?,?)";        $stmt = $this->Connect()->prepare($sql);        $stmt->bind_param('ssss', $studentId, $studentName, $date,$status);        $stmt->execute();    }    public function Attendance($studentId)    {//  This query will return only the attendance data from Monday to Friday of the current week and reset each week, ensuring it starts fresh every Monday.        $query = "       SELECT attendance.*FROM attendance         INNER JOIN student                    ON attendance.StudentNo = student.StudentNo         INNER JOIN parent                    ON student.StudentNo = parent.StudentNumberWHERE parent.StudentNumber = ?  AND WEEKDAY(attendance.Date) BETWEEN 0 AND 4  AND attendance.Date >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY;        ";        $stmt = $this->Connect()->prepare($query);        $stmt->bind_param('s',$studentId);        $stmt->execute();        $result = $stmt->get_result();        $attendance = [];        if ($result->num_rows > 0){ // check if we have result           while ($row = $result->fetch_assoc()){               $attendance[] = $row;           }            return  $attendance; // return the result if true        }        return  null;// else return null    }}