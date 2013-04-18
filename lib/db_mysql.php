<?

// функция получения ассоциативного массива из запроса MySQL
//класс работы с базой данных
class DatabaseConnection {
	
	public $db;
	public $result;
	
	// конструктор - выполняем при обьявлении нового экзкмпляра
	function __construct ($server, $user, $pass, $db, $autocommit = TRUE, $charset='utf8') {
		$this->db = new mysqli ($server, $user, $pass, $db);
		$this->db->set_charset ($charset);
		$this->db->autocommit ($autocommit);
		$this->result = NULL;
	}
	
	function query () {
		if     (func_num_args() == 1) $query = func_get_arg(0);
		elseif (func_num_args() == 2) { $request = func_get_arg(0); $query = func_get_arg(1); }
		elseif (func_num_args() == 3) { $request = func_get_arg(0); $index = func_get_arg(1); $query = func_get_arg(2); }
		else   return FALSE;
		
		$pre_result = $this->db->query($query);
		
		switch ($request):
			case 'assoc':
				// index - имя поля, значение которого будет являтся ключем ассоциативного массива
				if (!$index) while ($row = $pre_result->fetch_assoc()) $output[] = $row;
				else while ($row = $pre_result->fetch_assoc()) $output[$row[$index]] = $row;
				return $this->result = $output;
			break;
			
			case 'single':
				// index - имя поля, нулевое значение которого выйдет строковым результатом
				if (!$index) return FALSE;
				$pre_result = $pre_result->fetch_assoc();
				return $this->result = $pre_result[$index];
			break;
			
			case 'row':
				$pre_result = $pre_result->fetch_array();
				// брейка нет, далее делаем то же что и при дефолтном действии
			
			default:
				return $this->result = $pre_result;
		endswitch;
		
		$pre_result->free();
	}
	
	function commit () {
		if (!func_num_args()): $this->db->commit();
		else:
			switch (func_get_arg(0)):
				case 'auto': $this->db->autocommit(TRUE); break;
				case 'manual': $this->db->autocommit(FALSE); break;
			endswitch;
		endif;
	}
	
	function last () {
		return $this->db->insert_id;	
	}
	
	function free () {
		$this->result->free();	
	}
}
?>