<?php

use Phalcon\Mvc\Model;
//use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Client extends Model

{
	private $sqlprev='';
	private $sqlstringonly='';
	private $sqlg=array();
	private $sql2g=array();
	private $sqlg1=array();
	private $sql2g1=array();
	
	//NEWLY ADDED FOR SEARCH ALL
	private $sqlprev1='';
	private $searchall1=array();
	private $searchall2=array();
	private $searchAstringonly='';
		

    public function initialize() 
    {
		$this->setConnectionService('db4');
        $this->setSource("CRM__Client");
    }

	public function hideClient($id)
	{
		try {
            $sql='UPDATE client set hide=1 WHERE ag_id=:ag_id';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(':ag_id'=>$id), array('ag_id'=>\Phalcon\Db\Column::TYPE_INTEGER));
            return true;      
        } 
		catch (PDOException $e) {
          return $e->getMessage();
        } 
		
	}
	
	public function getClientNameId(){
		try {
         
            //$sql='SELECT distinct(a.id) as id,a.agregator as client FROM Agregators a INNER JOIN geo as g ON a.id=g.ag_id INNER JOIN Countries as c ON c.id=g.ct_id';
            $sql="SELECT a.id,a.agregator as client FROM Agregators as a INNER JOIN CRM__Client as c ON a.id=c.ag_id WHERE (agregator!='' OR agregator!=NULL) AND hide IS NULL ORDER BY agregator ASC ";
			$statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(), array());
            return $exe->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
          return $e->getMessage();
        } 
	}
	
	
	public function searchData($type='',$status='',$geo='',$io='',$am='',$id='',$content='',$search_value='',$order_column='',$order_dir='',$length='',$start='')
	{
		/*echo "type:";
		print_r($type);
		echo "status:".$status;
		echo "geo:";
		print_r($geo);
		echo "io:".$io;
		echo "id:".$id;
		echo "content:";
		print_r($content);
		echo "search_value:".$search_value;
		echo "order_column".$order_column;
		echo "order_dir".$order_dir;
		echo "length:".$length;
		echo "start:".$start;*/
		$sqlstring='';
		$str='';
		$sql=array();
		$sql2=array();
		if (!empty($type)) {
			$size=sizeof($type);
			$flag=0;
			//$str.="(";
			for($i=0;$i<$size;$i++)
			{
				if(!empty($type[$i])|| $type[$i]!=='All'){
					$flag=1;
					$str.="FIND_IN_SET(:type".$i.",c.type)>0 OR ";
					$sql[':type'.$i]=$type[$i];
					$sql2[':type'.$i]=\Phalcon\Db\Column::TYPE_VARCHAR;
				}
			}
			if($flag==1){
				$str=preg_replace('/\W\w+\s*(\W*)$/', '$1', $str);
				//$str.=")";
				$str="(".$str.")";
				$sqlstring .= $str." AND ";
			}
			
		}
		//for multiselect purpose 
		if (!empty($content)) {
			$str='';
			$size=sizeof($content);
			$flag=0;
			//$str.="(";
			for($i=0;$i<$size;$i++)
			{
				if(!empty($content[$i])|| $content[$i]!=='All'){
					$flag=1;
					$str.="FIND_IN_SET(:content".$i.",c.content)>0 OR ";
					$sql[':content'.$i]=$content[$i];
					$sql2[':content'.$i]=\Phalcon\Db\Column::TYPE_VARCHAR;
				}
			}
			if($flag==1){
				$str=preg_replace('/\W\w+\s*(\W*)$/', '$1', $str);
			//$str.=")";
				$str="(".$str.")";
				$sqlstring .= $str." AND ";
			}
		}

		if (!empty($status)) {
			$sqlstring .= "status=:status AND ";
			$sql[':status']=$status;
			$sql2[':status']=\Phalcon\Db\Column::TYPE_VARCHAR;
		}

		if (!empty($io)) {
			if($io=='Y'){
				$sqlstring .= "io IS NOT NULL AND ";
			}
			elseif($io=='N')
			{
				$sqlstring .= "io IS NULL AND ";
			}
		
		}
		if (!empty($am)) {
			$sqlstring .= "am=:am AND ";
			$sql[':am']=$am;
			$sql2[':am']=\Phalcon\Db\Column::TYPE_VARCHAR;
		}
		if (!empty($id)) {
			if($id!='true')
			{
				$sqlstring .= "c.ag_id=:id AND ";
				$sql[':id']=$id;
				$sql2[':id']=\Phalcon\Db\Column::TYPE_INTEGER;
			}
		}
		
		//for multiselect
		if (!empty($geo)) {
			$str='';
			$nextstr='';
			$size=sizeof($geo);
			$flag=0;
			for($i=0;$i<$size;$i++)
			{	
				if(!empty($geo[$i]) || $geo[$i]!=='All'){
					$flag=1;
					$nextstr.="ct_id=:ct_id".$i." OR ";
					$sql[':ct_id'.$i]=$geo[$i];
					$sql2[':ct_id'.$i]=\Phalcon\Db\Column::TYPE_VARCHAR;
				}
			}
			if($flag==1){
				$str.="g.ag_id IN(SELECT g.ag_id FROM CRM__Geo as g".(!empty($nextstr)? " WHERE " . $nextstr:'').")";
				$str=preg_replace('/\W\w+\s*(\W*)$/', '$1', $str);
				$sqlstring .= $str." AND ";
			}
		}
		
		$sqlstring= preg_replace('/\W\w+\s*(\W*)$/', '$1', $sqlstring);	
		$this->sqlstringonly=$sqlstring;
		
		$this->sqlg1=$sql;
		$this->sql2g1=$sql2;
		
		
		//dataTables section starts
		$search_value=$search_value;
		$order_column=$order_column;
		$order_dir=$order_dir;
		$length=$length;
		$start=$start;
		$variable='';
		$order=array('client','id','type','status','accountName','email','skype','geo','io','am',null);
		if($search_value!='')
		{
			
			$sqlstring.=" AND (
			g.ct_id LIKE :data 
			OR a.id LIKE :data  
			OR a.agregator LIKE :data  
			OR  c.email LIKE :data 
			OR c.type LIKE :data 
			OR c.status LIKE :data 
			OR  c.accountName LIKE :data 
			OR c.skype LIKE :data 
			OR c.io LIKE :data  
			OR c.am LIKE :data  )";
			$sql[':data']="%".$search_value."%";
			$sql2[':data']=\Phalcon\Db\Column::TYPE_VARCHAR;
		}
	
		
		$sqlstring.=" GROUP BY c.ag_id ";
		if($order_column!='')
		{
			$sqlstring.=" ORDER BY ".$order[$order_column] ." ". $order_dir;
		}
		else
		{
			$sqlstring.=" ORDER BY id ASC"; 
		}
		//$this->sql_nolimit="SELECT username,company_name from client".(!empty($this->sql_string)? " WHERE " . $this->sql_string:'');
		$this->sqlprev=$sqlstring;
		$this->sqlg=$sql;
		$this->sql2g=$sql2;
		if($length!='')
		{
			//$sqlstring.=" LIMIT ".$start.",".$length;
			$sqlstring.=" LIMIT :start,:length"; 
			$sql[':start']=(int) trim($start);
			$sql2[':start']=\Phalcon\Db\Column::TYPE_INTEGER;
			$sql[':length']=(int) trim($length);
			$sql2[':length']=\Phalcon\Db\Column::TYPE_INTEGER;
		}
		
		//dataTables section ends
		
		$checksqlstring='';
		if($this->sqlstringonly!=''){
			$checksqlstring=" WHERE hide IS NULL AND  " . $sqlstring;
		}
		else
		{	
			$checksqlstring=" WHERE hide IS NULL ".$sqlstring;
		}
		
		$sql1="SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email,c.private_profile as private_profile,c.type, c.status,c.accountName,c.skype,c.io, c.am FROM CRM__Client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id LEFT JOIN CRM__Geo AS g ON g.ag_id=a.id".(!empty($checksqlstring)? $checksqlstring:'');
		try {
		//$sql1="SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email, c.type, c.status,c.accountName,c.skype,c.io, c.am FROM (SELECT * FROM client " . (!empty($sqlstring)? " WHERE " . $sqlstring:'').") AS c INNER JOIN Agregators AS a ON c.ag_id=a.id INNER JOIN (SELECT * FROM geo " . (!empty($string)? " WHERE " . $string:'')." ) AS g ON g.ag_id=c.ag_id GROUP BY c.ag_id LIMIT ".$limit."," .$increase_value ." ";
		//echo $sql1;
		$statement = $this->getDi()->getDb4()->prepare($sql1);
        $exe = $this->getDi()->getDb4()->executePrepared($statement,$sql,$sql2);
        return $exe->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
          return $e->getMessage();
        } 
	}

	public function get_all_data_row(){
		
		try {
		$sql="Select count(*) as numrow
			From
			(
				SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email,c.private_profile as private_profile,c.type, c.status,c.accountName,c.skype,c.io, c.am FROM CRM__Client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id LEFT JOIN CRM__Geo AS g ON g.ag_id=a.id".(!empty($this->sqlstringonly)? " WHERE hide IS NULL AND " . $this->sqlstringonly:'')." GROUP BY c.ag_id
    
			) as rows";
		$statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement,$this->sqlg1,$this->sql2g1);
        return $exe->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
          return $e->getMessage();
        } 	
	}
	
	public function get_filtered_data_row(){
		$checksqlstring='';
		if($this->sqlstringonly!=''){
			$checksqlstring=" WHERE hide IS NULL AND " .$this->sqlprev;
		}
		else
		{	
			$checksqlstring=" WHERE hide IS NULL ".$this->sqlprev;
		}
		try{
		$sql="Select count(*) as numrow
			From
			(
				SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email,c.private_profile as private_profile,c.type, c.status,c.accountName,c.skype,c.io, c.am FROM CRM__Client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id LEFT JOIN CRM__Geo AS g ON g.ag_id=a.id".(!empty($checksqlstring)? $checksqlstring:'')."
    		) as rows";
		$statement = $this->getDi()->getDb4()->prepare($sql);
		$exe = $this->getDi()->getDb4()->executePrepared($statement,$this->sqlg,$this->sql2g);
		return $exe->fetchAll(PDO::FETCH_ASSOC);
					
		}
		catch(PDOException $e){
			return $e->getMessage();
		}
	}
	
	public function searchAll($searchvalue,$search_value,$order_column,$order_dir,$length,$start){
		
		$sqlstring='';
		$sql=array();
		$sql2=array();
		//dataTables section starts
	
		$search_value=$searchvalue;
		$order=array('client','id','type','status','accountName','email','skype','geo','io','am',null);
		if($search_value!='')
		{
			$sqlstring.="
			g.ct_id LIKE :data 
			OR a.id LIKE :data  
			OR a.agregator LIKE :data  
			OR  c.email LIKE :data 
			OR c.type LIKE :data 
			OR c.status LIKE :data 
			OR  c.accountName LIKE :data 
			OR c.skype LIKE :data 
			OR c.io LIKE :data  
			OR c.am LIKE :data ";
			$sqlstring ="(".$sqlstring.")";
			$sql[':data']="%".$search_value."%";
			$sql2[':data']=\Phalcon\Db\Column::TYPE_VARCHAR;
		}
		$this->searchAstringonly=$sqlstring;
		
		$sqlstring.=" GROUP BY c.ag_id ";
		if($order_column!='')
		{
			$sqlstring.=" ORDER BY ".$order[$order_column] ." ". $order_dir;
		}
		else
		{
			$sqlstring.=" ORDER BY id ASC"; 
		}
		//$this->sql_nolimit="SELECT username,company_name from client".(!empty($this->sql_string)? " WHERE " . $this->sql_string:'');
		$this->sqlprev1=$sqlstring;
		$this->searchall1=$sql;
		$this->searchall2=$sql2;
		if($length!='')
		{
			//$sqlstring.=" LIMIT ".$start.",".$length;
			$sqlstring.=" LIMIT :start,:length"; 
			$sql[':start']=(int) trim($start);
			$sql2[':start']=\Phalcon\Db\Column::TYPE_INTEGER;
			$sql[':length']=(int) trim($length);
			$sql2[':length']=\Phalcon\Db\Column::TYPE_INTEGER;
		}
		
		//dataTables section ends
		$sql1="SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email,c.private_profile as private_profile,c.type, c.status,c.accountName,c.skype,c.io, c.am FROM CRM__Client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id LEFT JOIN CRM__geo AS g ON g.ag_id=a.id".(!empty($sqlstring)? " WHERE hide IS NULL AND  ".$sqlstring:'');
		try {
		//$sql1="SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email, c.type, c.status,c.accountName,c.skype,c.io, c.am FROM (SELECT * FROM client " . (!empty($sqlstring)? " WHERE " . $sqlstring:'').") AS c INNER JOIN Agregators AS a ON c.ag_id=a.id INNER JOIN (SELECT * FROM geo " . (!empty($string)? " WHERE " . $string:'')." ) AS g ON g.ag_id=c.ag_id GROUP BY c.ag_id LIMIT ".$limit."," .$increase_value ." ";
		
		$statement = $this->getDi()->getDb4()->prepare($sql1);
        $exe = $this->getDi()->getDb4()->executePrepared($statement,$sql,$sql2);
        return $exe->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
          return $e->getMessage();
        } 	
	}
	
	public function get_searchall_data_row(){
		try {
		$sql="Select count(*) as numrow
			From
			(
				SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email,c.private_profile as private_profile,c.type, c.status,c.accountName,c.skype,c.io, c.am FROM CRM__Client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id LEFT JOIN CRM__Geo AS g ON g.ag_id=a.id".(!empty($this->searchAstringonly)? " WHERE hide IS NULL AND " . $this->searchAstringonly:'')." GROUP BY c.ag_id
    
			) as rows";
		$statement = $this->getDi()->getDb4()->prepare($sql);
        $exe = $this->getDi()->getDb4()->executePrepared($statement,$this->searchall1,$this->searchall2);
        return $exe->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
          return $e->getMessage();
        } 	
	}
	
	public function get_searchallfiltered_data_row(){
		
		
		try{
		$sql="Select count(*) as numrow
			From
			(
				SELECT group_concat(g.ct_id) AS geo, a.id AS id,a.agregator AS client,c.email,c.private_profile as private_profile,c.type, c.status,c.accountName,c.skype,c.io, c.am FROM CRM__Client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id LEFT JOIN CRM__Geo AS g ON g.ag_id=a.id".(!empty($this->sqlprev1)? " WHERE hide IS NULL AND ".$this->sqlprev1:'')."
    		) as rows";
		$statement = $this->getDi()->getDb4()->prepare($sql);
		$exe = $this->getDi()->getDb4()->executePrepared($statement,$this->searchall1,$this->searchall2);
		return $exe->fetchAll(PDO::FETCH_ASSOC);
					
		}
		catch(PDOException $e){
			return $e->getMessage();
		}
		
		
		
	}
	
		
	function saveClient($clientData,$geo){
		if (isset($clientData)) {
			$company_name="";
			$columns="";
			$values="";
			$values1="";
			$sql1=array();
			$sql2=array();
			foreach($clientData as $key => $value)
			{
				if($key=='company_name')
				{
					if(!empty($value))
					{
						$company_name=$value;
					}
				}
				else{
					if (!empty($value)) 
					{
						$columns .= $key.",";
						//$values .="'".$value."',";
						$values1 .=":".$key.",";	
						$sql1[":".$key]=$value;
						$sql2[":".$key]=\Phalcon\Db\Column::TYPE_VARCHAR;
					}	
				}						
			}
			
			if(!empty($company_name))
			{
				//write code for file upload
				
				
		
				try {
				//check whether agregators exits or not
					$sql="SELECT id FROM Agregators where agregator=:company_name";	
					$statement = $this->getDi()->getDb4()->prepare($sql);
					$exe = $this->getDi()->getDb4()->executePrepared($statement, array('company_name'=>$company_name), array('company_name'=>\Phalcon\Db\Column::TYPE_VARCHAR));
					$get_data=$exe->fetchAll(PDO::FETCH_ASSOC);
					if($get_data!=false){
						//$this->flash->success("Duplicate entry!");
					 return false;
					 //send msg for duplicate entry
					}
					else{
						
					 // save to agregator table
					 
						$sql ="INSERT INTO Agregators(agregator) values(:company_name)";
						$statement = $this->getDi()->getDb4()->prepare($sql);
						$exe = $this->getDi()->getDb4()->executePrepared($statement, array('company_name'=>$company_name), array('company_name'=>\Phalcon\Db\Column::TYPE_VARCHAR));
						$sql="SELECT id FROM Agregators where agregator=:company_name";	
						$statement = $this->getDi()->getDb4()->prepare($sql);
						$exe = $this->getDi()->getDb4()->executePrepared($statement, array('company_name'=>$company_name), array('company_name'=>\Phalcon\Db\Column::TYPE_VARCHAR));
						$get_data=$exe->fetchAll(PDO::FETCH_ASSOC);
						foreach($get_data as $value)
						{
							$id=$value['id'];
							
						}
						
						//save to geo table
						
						if(isset($geo))
						{
							foreach($geo as $value){
								
								$sql ="INSERT INTO geo(ct_id,ag_id) values(:country,:agregator)";
								$statement = $this->getDi()->getDb4()->prepare($sql);
								$exe = $this->getDi()->getDb4()->executePrepared($statement, array('country'=>$value,'agregator'=>$id), array('country'=>\Phalcon\Db\Column::TYPE_VARCHAR,'agregator'=>\Phalcon\Db\Column::TYPE_INTEGER));
								
							}
							
						}
						
						$columns .='ag_id';
						//$values .=$id;
						$values1 .=':ag_id';
						$sql1[':ag_id'] =$id;
						$sql2[':ag_id'] =\Phalcon\Db\Column::TYPE_INTEGER;
						try{
							$sql ="INSERT INTO client(".$columns.") values(".$values1.")";
							$statement = $this->getDi()->getDb4()->prepare($sql);
							$exe = $this->getDi()->getDb4()->executePrepared($statement,$sql1,$sql2);
						}
						catch (PDOException $e) 
						{
							echo $e->getMessage();
						} 
						return true;
					
					}			 
				}
				catch (PDOException $e) 
				{
					echo $e->getMessage();
				} 
			}
		}
		
	
	}
	
	function getClientDetailById($id){

		try {
            $sql='SELECT * FROM client as c INNER JOIN Agregators as a ON a.id=c.ag_id WHERE ag_id=:ag_id AND hide IS NULL';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(':ag_id'=>$id), array('ag_id'=>\Phalcon\Db\Column::TYPE_INTEGER));
            return $exe->fetchAll(PDO::FETCH_ASSOC);         
        } 
		catch (PDOException $e) {
          return $e->getMessage();
        } 
		
		
	}

	function get_client_geo($id){

		try { 
            $sql='SELECT ct_id FROM geo WHERE  ag_id=:ag_id';
            $statement = $this->getDi()->getDb4()->prepare($sql);
            $exe = $this->getDi()->getDb4()->executePrepared($statement, array(':ag_id'=>$id), array('ag_id'=>\Phalcon\Db\Column::TYPE_INTEGER));
            return $exe->fetchAll(PDO::FETCH_ASSOC);     
        } 
		catch (PDOException $e) {
          return $e->getMessage();
        } 
		

	}

	function getClientUpdated($clientData,$geo){
        if (isset($clientData)) {
			$company_name="";
			$ag_id="";
			$string="";
			$sql1=array();
			$sql2=array();
			foreach($clientData as $key => $value)
			{
				if($key=='company_name')
				{
					if(!empty($value))
					{
						$company_name=$value;
					}
				}
				if($key=='ag_id')
				{
					$ag_id=$value;
				}
				elseif($key=='io')
				{
					if(!empty($value))
					{
						$string.=$key."=:".$key.", ";
						$sql1[":".$key]=$value;
						$sql2[":".$key]=\Phalcon\Db\Column::TYPE_VARCHAR;	
					}
				}
				else
				{
					
					$string.=$key."=:".$key.", ";
					$sql1[":".$key]=$value;
					$sql2[":".$key]=\Phalcon\Db\Column::TYPE_VARCHAR;	
					
				}

			}

			//this section is not necessary please remove this
			//$string= preg_replace('/\W\w+\s*(\W*)$/', '$1', $string);
			$sql1[':ag_id'] =$ag_id;
			$sql1[':company_name']=$company_name;
			$sql2[':ag_id'] =\Phalcon\Db\Column::TYPE_INTEGER;
			$sql2[':company_name'] =\Phalcon\Db\Column::TYPE_VARCHAR;
			if(!empty($company_name))
			{
				try {
					$sql="SELECT id FROM Agregators where agregator=:company_name AND id!=:id";	
					$statement = $this->getDi()->getDb4()->prepare($sql);
					$exe = $this->getDi()->getDb4()->executePrepared($statement, array('company_name'=>$company_name,'id'=>$ag_id), array('company_name'=>\Phalcon\Db\Column::TYPE_VARCHAR,'company_name'=>\Phalcon\Db\Column::TYPE_INTEGER));
					$get_data=$exe->fetchAll(PDO::FETCH_ASSOC);
					if($get_data!=false){
						//$this->flash->success("Duplicate entry!");
					 return false;
					 //send msg for duplicate entry
					}
					else{
						$sql="UPDATE client AS c INNER JOIN Agregators AS a ON c.ag_id=a.id SET ".$string."a.agregator=:company_name WHERE c.ag_id=:ag_id";
						$statement = $this->getDi()->getDb4()->prepare($sql);
						$exe = $this->getDi()->getDb4()->executePrepared($statement,$sql1,$sql2);
						//geo code here
						if(isset($geo))
						{
							$sql ="DELETE FROM geo WHERE ag_id=:ag_id";
							$statement = $this->getDi()->getDb4()->prepare($sql);
							$exe = $this->getDi()->getDb4()->executePrepared($statement, array('ag_id'=>$ag_id), array('ag_id'=>\Phalcon\Db\Column::TYPE_INTEGER));
							
							foreach($geo as $value){
								$sql ="INSERT INTO geo(ct_id,ag_id) values(:ct_id,:ag_id)";
								$statement = $this->getDi()->getDb4()->prepare($sql);
								$exe = $this->getDi()->getDb4()->executePrepared($statement, array('ct_id'=>$value,'ag_id'=>$ag_id), array('ct_id'=>\Phalcon\Db\Column::TYPE_VARCHAR,'ag_id'=>\Phalcon\Db\Column::TYPE_INTEGER));
								
							}
						}

						return true;
					}

				}
				catch (PDOException $e) {
          			return $e->getMessage();
        		}
			}	 
		}	
	}

}