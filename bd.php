<?php
/*
DBClass v1.1
Author: King; 2009.2.12
*/

class DBClass{

	public $sql;	//sql
	public $pagesize;
	public $page;
	public $count;
	public $pagename = 'page';
	public $link;
	//protected $maxpage;
	public $maxpage;
	protected $url;
	protected $result;

	public function __construct($pagesize=0,$pagename='page'){
		$this->pagesize=$pagesize;
		$this->pagename=$pagename;
		if($pagesize){
			$this->page=$this->currentpage();
		}
	}//end function

	//connect mysql:($dbname,$dbuser, $dbpw)
	public function connect($dbname,$dbuser, $dbpw, $dbcharset = '', $dbhost='localhost', $pconnect = 0) 	{
		if($pconnect) {
			if(!$this->link = mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = mysql_connect($dbhost, $dbuser, $dbpw,1)) {
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') {

			if($dbcharset) {
				mysql_query("SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') {
				mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($dbname) {
			mysql_select_db($dbname, $this->link);
		}
	}//end function

	//return the version of mysql
	protected function version() {
		return mysql_get_server_info($this->link);
	}//end function

	//execute sql,save the result contain 'select'
	public function execute($sql=''){
		//if($sql!='') $this->sql=trim(eregi_replace('from','from',$sql));
		$this->sql=$sql;
		$flag=strtolower(substr($this->sql,0,6));
		switch($flag){
			case 'select':

				if($this->pagesize){
					$this->page=$this->currentpage();
					$firstcount=($this->page-1)*$this->pagesize;
	     			$nsql=$this->sql." limit $firstcount,$this->pagesize";
				}
				else{
					$nsql=$this->sql;
				}
				return $this->result=mysql_query($nsql,$this->link);

				break;
			case 'insert':
				mysql_query($this->sql,$this->link);
				return mysql_insert_id($this->link);
				break;
			default:
				return mysql_query($this->sql,$this->link);
				break;
		}
	}//end function

	//get max record;
	public function recordcount()
	{
		if(!$this->count){
			//$this->sql=trim(eregi_replace('from','from',$this->sql));
			$this->sql=$this->sql;
			$sql='select count(*) as _all '.substr($this->sql,strpos($this->sql,'from'));

			 if(strpos($sql,'order by'))
			  {
					$a=explode('order by',$sql);
					$sql=$a[0];
			  }
			  	//echo "dfff".$sql;

		//	$sql=substr($this->sql,strpos($this->sql,'from'));
//			if(strpos($sql,'order by')){
//				$sql=substr($this->sql,strpos($this->sql,'order by'));
//			}else{
//				$sql='select count(*) as _all '.substr($this->sql,strpos($this->sql,'from'));
//			}

			//echo $sql.strpos($sql,'order by')."<br/>";
			$result=mysql_query($sql,$this->link);
			$rs=$this->fetch_array($result);
			$this->count=$rs["_all"];
		}
		return $this->count;
	 }//end function

	 //get the max page;
	 public function maxpage()
	 {
	 	if(!$this->maxpage){
			if($this->recordcount() % $this->pagesize==0)
			{
				$this->maxpage=($this->recordcount()/$this->pagesize);
			}
			else
			{
				$this->maxpage=intval($this->recordcount()/$this->pagesize)+1;
			}
		}
		return $this->maxpage;
	 }//end function

	 public function currentpage()
	 {
	 	$this->page=!empty($_REQUEST[$this->pagename])?intval($_REQUEST[$this->pagename]):1;
			$this->page=$this->page>$this->maxpage()?$this->maxpage():$this->page;
			$this->page=$this->page<1?1:$this->page;
		return $this->page;
	 }//end function

	//fetch array from connection
	public function fetch_array($query=0, $result_type = MYSQL_ASSOC) {
		if(!$query){
			if($this->result!='' && $this->result!=NULL)
				return mysql_fetch_array($this->result,$result_type);
		}else{
			return mysql_fetch_array($query, $result_type);
		}
	}//end function

	function query($sql, $type='')
	{
			$func = $type == 'UNBUFFERED' ? 'mysql_unbuffered_query' : 'mysql_query';
			if(!($querys = @$func($sql , $this->link)) && $type != 'SLIENT')
			{
				$this->halt('MySQL Query Error', $sql);
				return false;
			}
			return $querys;
	}

	function insert($tablename, $array)
	{
		    $sql = "INSERT INTO $tablename(".implode(',', array_keys($array)).") VALUES('".implode("','", $array)."')";
			//die($sql);
			if ($this->query($sql))
			{
			    return $this->insertId();

			}
			else
			{
			    return false;
			}
	}

	function getOne($sql, $limited = false)//获得单条记录
    {
	//die($sql);
        	if ($limited == true)
        	{
            	$sql = trim($sql . ' LIMIT 1');
        	}
        	$res = $this->query($sql);
        	if ($res !== false)
        	{
            	$row = mysql_fetch_row($res);//mysql_fetch_row不会合并相同记录

            	if ($row !== false)
            	{
					return $row[0];
           	 	}
            	else
            	{
                	return '';
            	}
        	}
        	else
        	{
            	return '';
        	}
   }
    //获取所有数据填充到数组
	  function getAll($sql)
	  {
	     $res = $this->query($sql);
		 if ($res !== false)
		 {
		    $arr = array();
			while($row = mysql_fetch_assoc($res))
			{
			   $arr[] = $row;
			}
			return $arr;
		 }
		 else
		 {
		    return array();
		 }
	  }

	   //获取单行记录填充到数组
	  function getRow($sql, $limit = false)
	  {
	      if($limit == true)
		  {
		     $sql =trim($sql . ' LIMIT 1');
		  }
		  $res = $this->query($sql);
          if ($res !== false)
		  {
		     return mysql_fetch_assoc($res);
		  }
		  else
		  {
		     return array();
		  }

	  }

	 function fetchArray($query, $result_type = MYSQL_ASSOC)
	 {
		return mysql_fetch_array($query, $result_type);
	 }

	function insertId()
	{
		return mysql_insert_id($this->link);
	}
	function update($tablename, $array, $where = '')
	{
			if($where)
			{
				$sql = '';
				foreach($array as $k=>$v)
				{
					$sql .= ", $k ='$v'";
				}
				$sql = substr($sql, 1);
				$sql = "UPDATE $tablename SET $sql WHERE $where";
			}
			else
			{
				$sql = "REPLACE INTO $tablename(".implode(',', array_keys($array)).") VALUES('".implode("','", $array)."')";
			}
			//die($sql);
			return $this->query($sql);
	}
	//free result
	public function free_result($result=null){
		if($result==null){
			if(is_resource($this->result))
				mysql_free_result($this->result);
		}
		else{
			mysql_free_result($result);
		}
	}//end function

	//close mysql
	public function close() {
		if(is_resource($this->link))
			return mysql_close($this->link);
	}//end function

	//return error information
	public function halt($message = '', $sql = '') {
		exit($message.'<br /><br />'.$sql.'<br /> '.mysql_error());
	}//end function

	/*
	protected function getUrl()
	{


		if(!$this->url)
		{
			$url=$_SERVER['REQUEST_URI'];
			$this->url=strpos($url,'?')?$url:$url.'?';
			$this->url=str_replace('&'.$this->pagename.'='.$_GET[$this->pagename],'',$this->url);
		}
		return $this->url;
	}//end function
	*/

	protected function getUrl()
	{
		if(!$this->url)
		{
			$url=$_SERVER['REQUEST_URI'];
			$this->url=strpos($url,'?')?$url:$url.'?';
			$_REQUEST[$this->pagename] = isset($_REQUEST[$this->pagename]) ? $_REQUEST[$this->pagename] : 1;
			$this->url=str_replace('&'.$this->pagename.'='.$_REQUEST[$this->pagename],'',$this->url);
		}
		return $this->url;
	}//end function


	//the url must contain the symbol of "$$"; $$ will be replaced by page num;Chinese;
	public function pageindex($url)
	{

		$strpage='';
		$strpage.='<div class="pages">';
		$strpage.=' 总记录数:'.$this->recordcount().';';
		$strpage.=' 总页数:'.$this->maxpage().';';
		$strpage.=" 每页$this->pagesize 条".';';
		$strpage.=' <a href="'.str_replace('$$','1',$url).'" title="首页">首页</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" title="上一页">上一页</a> ';

		//get startpage and endpage
		$startPage=$this->page-4;
		$endPage=$this->page+5;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=10;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-9;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<10)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.=$i.'&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).'" title="第'.$i.'页">['.$i.']</a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'" title="下一页">下一页</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).'" title="首页">尾页</a></div>';
		return $strpage;
	}//end function
	//the url must contain the symbol of "$$"; $$ will be replaced by page num;Chinese;
	public function pageindexs($url)
	{
		$strpage='';
		$strpage.='<div class="paging">';
		//$strpage.=' 总页数:'.$this->maxpage().';';
		//$strpage.=' <a href="'.str_replace('$$','1',$url).'" title="首页">首页</a>';
		//$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" title="上一页">上一页</a> ';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" class="previous"><span class="Bg"><b>&nbsp;</b></span></a> ';

		//get startpage and endpage
		$startPage=$this->page-4;
		$endPage=$this->page+5;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=10;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-9;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<10)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.='<a class="cur" ><span class="Bg"><b>'.$i.'</b></span></a>&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).'" title="第'.$i.'页"><span class="Bg"><b>'.$i.'</b></span></a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'"  class="nextpage" title="下一页"><span class="Bg"><b>&nbsp;</b></span></a>';
		//$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'" title="下一页">下一页</a>';
		//$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).'" title="首页">尾页</a></div>';
		return $strpage;
	}//end function

   public function pageindexes($url)
	{
				$strpage='';
		$strpage.='<div class="pages">';
/*		$strpage.=' <a href="'.str_replace('$$','1',$url).'" title="首页">首页</a>';
*/		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" title="上一页">上一页</a> ';

		//get startpage and endpage
		$startPage=$this->page-4;
		$endPage=$this->page+5;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=10;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-9;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<10)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
/*				$strpage.='<a class="atpage" style="color:#F00; font-size:20px; font-weight:bold; padding-top:0px; border:0px;">'.$i.'</a>&nbsp;';
*/			}
			else
			{
/*				$strpage.='<a href="'.str_replace('$$',$i,$url).'" title="第'.$i.'页">'.$i.'</a>&nbsp;';
*/			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'" title="下一页">下一页</a>';
/*		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).'" title="首页">尾页</a></div>';
*/		return $strpage;
	}//end function
	//the url must contain the symbol of "$$"; $$ will be replaced by page num;Chinese;
	public function pageindex_mobile($url)
	{


	    $strpage = '';
		$strpage.="<div class='list_page'>";
		if($this->page==1){
			$strpage.="<div class='disabled' title='已经是第一页'>上一页</div>";
		}else{
			$strpage.="<div class=' '><a href='".str_replace('%',$this->page-1,$url)."' title='上一页'>上一页</a></div>";
		}
		$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
		if($this->page>=$this->maxpage()){
			$strpage.="<div class='disabled' title='已经是最后一页'>下一页</div>";
		}else{
			$strpage.="<div class=' '><a href='".str_replace('%',$this->page+1,$url)."' title='下一页'>下一页</a></div>";
		}
		$strpage.="</div>";

		return $strpage;
	}//end function


	public function pageindex_shop($url)
	{


	    $strpage = '';
		$strpage.="<div class='page_a' style='text-align: center;'>";
		if($this->page==1){
			if($this->maxpage()>$this->page){ //有下一页
			$strpage.="<a class='ll' href='".str_replace('%',$this->page+1,$url)."' style='width: 89%; display: inline-block; float: none;' title='下一页'>下一页</a>";
			}
		}else{
			if($this->page<>$this->maxpage()){
				$strpage.="<a class='ll' href='".str_replace('%',$this->page-1,$url)."' title='上一页'>上一页</a>";
			}
		}
		//$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
		if($this->page>=$this->maxpage()){
			if($this->maxpage()>$this->page){ //有下一页
			 $strpage.="<a class='rr' href='".str_replace('%',$this->page+1,$url)."' style='width: 89%; display: inline-block; float: none;' title='下一页'>下一页</a>";
			}else{
			 $strpage.="<a class='ll' href='".str_replace('%',$this->page-1,$url)."' style='width: 89%; display: inline-block; float: none;' title='上一页'>上一页</a>";
			}
		}else{
			if($this->page<>1){
				$strpage.="<a class='rr' href='".str_replace('%',$this->page+1,$url)."' title='下一页'>下一页</a>";
			}
		}
		$strpage.="</div>";

		return $strpage;
	}//end function

	public function pageindex_mobile_news($url)
	{
		// <div class="pageer">
                          //  <div class="page_left"><a href="#"><img src="images/page_left.png"></a></div>
                            // <div class="page_right"><a href="#"><img src="images/page_right.png"></a></div>
							 //</div>

	    $strpage = '';
		$strpage.="<div class='pageer'>";
		if($this->page==1){
			$strpage.="<div class='page_left' title='已经是第一页'><img src='/template/show_02/images/page_left.png'></div>";
		}else{
			$strpage.="<div class='page_left'><a href='".str_replace('%',$this->page-1,$url)."' title='上一页'><img src='/template/show_02/images/page_left.png'></a></div>";
		}
		//$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
		if($this->page>=$this->maxpage()){
			$strpage.="<div class='page_right' title='已经是最后一页'><img src'/template/show_02/images/page_right.png'></div>";
		}else{
			$strpage.="<div class='page_right'><a href='".str_replace('%',$this->page+1,$url)."' title='下一页'><img src'/template/show_02/images/page_right.png'></a></div>";
		}
		$strpage.="</div>";

		return $strpage;
	}//end function


	public function pageindex_new($url)
	{
// <div class="list_page">
//           <div class=" disabled ">上一页</div>
//           <div class="allpage"><div class="currentpage">1/2</div>
//           </div>
//           <div class=" "> <a href="news.shtml?id=156&amp;nid=&amp;page=2">下一页</a></div>
//      </div>
//
//</div>


	    $strpage = '';
		$strpage.="<div style='float:right;padding-right:10px;'>";
		if($this->page==1){
			$strpage.="<img title='已经是第一页' src='/members/images/page-1.png' />&nbsp;&nbsp;&nbsp;";
		}else{
			$strpage.="<a href='".str_replace('%',$this->page-1,$url)."' title='上一页'><img src='/members/images/page-1.png' /></a>&nbsp;&nbsp;&nbsp;";
		}
		//$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
//		for($i=$startPage;$i<=$endPage;$i++)
//		{
//			if($i==$this->page)
//			{
//				$strpage.=$this->page."/".$this->maxpage();
//			}
//			else
//			{
//				$strpage.=$this->page."/".$this->maxpage();
//			}
//		}
		if($this->page==$this->maxpage()){
			$strpage.="<img title='已经是最后一页' src='/members/images/page-2.png' />";
		}else{
			$strpage.="<a href='".str_replace('%',$this->page+1,$url)."' title='下一页'><img src='/members/images/page-2.png' /></a>";
		}
		$strpage.="</div>";

		return $strpage;
	}//end function


	//the url must contain the symbol of "$$"; $$ will be replaced by page num. English;
	public function pageEnglishIndex($url,$anchor='')
	{
		if($anchor!='')
			$anchor="#".$anchor;

		$strpage='';
		$strpage.= '<div class="luotong_page">';
		$strpage.=' Total:'.$this->recordcount().';';
		$strpage.=' Total Pages:'.$this->maxpage().';';
		$strpage.=" One Page:$this->pagesize".';';
		$strpage.=' <a href="'.str_replace('$$','1',$url).$anchor.'" title="First Page">First</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).$anchor.'" title="Previous Page">Previous</a> </div>';
		//get startpage and endpage
		$startPage=$this->page-3;
		$endPage=$this->page+3;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=7;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-6;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<7)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.=$i.'&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).$anchor.'" title="'.$i.'">['.$i.']</a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).$anchor.'" title="Next Page">Next</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).$anchor.'" title="End Page">End</a></div>';
		return $strpage;
	}//end function
	//the url must contain the symbol of "$$"; $$ will be replaced by page num. English;
	public function pageEnglishIndexs($url,$anchor='')
	{
		if($anchor!='')
			$anchor="#".$anchor;

		$strpage='';
		$strpage.= '<div class="luotong_page">';
		$strpage.=' Total:'.$this->recordcount().';';
		$strpage.=' Total Pages:'.$this->maxpage().';';
		$strpage.=" One Page:$this->pagesize".';';
		$strpage.=' <a href="'.str_replace('$$','1',$url).$anchor.'" title="First Page">First</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).$anchor.'" title="Previous Page">Previous</a> </div>';
		//get startpage and endpage
		$startPage=$this->page-3;
		$endPage=$this->page+3;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=7;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-6;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<7)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.=$i.'&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).$anchor.'" title="'.$i.'">['.$i.']</a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).$anchor.'" title="Next Page">Next</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).$anchor.'" title="End Page">End</a></div>';
		return $strpage;
	}//end function

	public function showpage($language=null,$anchor='')
	{
		echo $this->strpage($language,$anchor);

	}//end function
	public function showpages($language=null,$anchor='')
	{
		echo $this->strpages($language,$anchor);

	}//end function
	public function showpagees($language=null,$anchor='')
	{
		echo $this->strpagees($language,$anchor);

	}//end function

	public function strpage($language=null,$anchor=''){
		if($this->pagesize){
			switch($language)
			{
				case 'English':
					return $this->pageEnglishIndex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
				default:
					return $this->pageindex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
			}
		}
	}
	public function strpages($language=null,$anchor=''){
		if($this->pagesize){
			switch($language)
			{
				case 'English':
					return $this->pageEnglishIndex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
				default:
					return $this->pageindexs($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
			}
		}
	}

	public function strpagees($language=null,$anchor=''){
		if($this->pagesize){
			switch($language)
			{
				case 'English':
					return $this->pageEnglishIndex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
				default:
					return $this->pageindexes($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
			}
		}
	}

	//得到表的字段名，并返回字段名数组
	function getCol($DESC){
        $res = mysql_query($DESC,$this->link);
        if ($res !== false){
            $arr = array();
            while ($row = mysql_fetch_row($res))
            {
                $arr[] = $row[0];
            }

            return $arr;
        }
        else
        {
            return false;
        }
    }//end function

//将表单数组数据更新到数据库,$table:表名，$field_array：表单值数组；$mode:操作开关，默认为insert;where，查询语句
  	function autoExecute($table,$field_array,$mode='insert',$where=''){
		$field_names=$this->getCol(' DESC '.$table);
		$sql = '';
		switch($mode){
	  	case 'insert':
	   		$fields=$values=array();
	   		foreach($field_names as $value){
	   			if(array_key_exists($value,$field_array)==true && $field_array[$value]!='')
	   			{
	     			$fields[]=$value;
		 			$values[]="'".addslashes($field_array[$value])."'";
	   			}
	  		}
			if (!empty($fields))
       		{
        		$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
       		}
			//die($sql);
			break;
    	case 'update':
	  		$sets = array();
            foreach ($field_names as $value)
            {
                if (array_key_exists($value, $field_array) == true)
                {
                    $sets[] = $value . " = '" . addslashes($field_array[$value]) . "'";
                }
            }

            if (!empty($sets))
            {
                $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
				//echo $sql;
            }
	  		break;
   		}
   		if ($sql)
        {
            mysql_query($sql,$this->link);
			return mysql_insert_id();
        }
        else
        {
            return false;
        }
  	}//end function


	public function __destruct(){
		$this->free_result();
		$this->close();
	}//end function
}//end class
?><?php
/*
DBClass v1.1
Author: King; 2009.2.12
*/

class DBClass{

	public $sql;	//sql
	public $pagesize;
	public $page;
	public $count;
	public $pagename = 'page';
	public $link;
	//protected $maxpage;
	public $maxpage;
	protected $url;
	protected $result;

	public function __construct($pagesize=0,$pagename='page'){
		$this->pagesize=$pagesize;
		$this->pagename=$pagename;
		if($pagesize){
			$this->page=$this->currentpage();
		}
	}//end function

	//connect mysql:($dbname,$dbuser, $dbpw)
	public function connect($dbname,$dbuser, $dbpw, $dbcharset = '', $dbhost='localhost', $pconnect = 0) 	{
		if($pconnect) {
			if(!$this->link = mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = mysql_connect($dbhost, $dbuser, $dbpw,1)) {
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') {

			if($dbcharset) {
				mysql_query("SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') {
				mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($dbname) {
			mysql_select_db($dbname, $this->link);
		}
	}//end function

	//return the version of mysql
	protected function version() {
		return mysql_get_server_info($this->link);
	}//end function

	//execute sql,save the result contain 'select'
	public function execute($sql=''){
		//if($sql!='') $this->sql=trim(eregi_replace('from','from',$sql));
		$this->sql=$sql;
		$flag=strtolower(substr($this->sql,0,6));
		switch($flag){
			case 'select':

				if($this->pagesize){
					$this->page=$this->currentpage();
					$firstcount=($this->page-1)*$this->pagesize;
	     			$nsql=$this->sql." limit $firstcount,$this->pagesize";
				}
				else{
					$nsql=$this->sql;
				}
				return $this->result=mysql_query($nsql,$this->link);

				break;
			case 'insert':
				mysql_query($this->sql,$this->link);
				return mysql_insert_id($this->link);
				break;
			default:
				return mysql_query($this->sql,$this->link);
				break;
		}
	}//end function

	//get max record;
	public function recordcount()
	{
		if(!$this->count){
			//$this->sql=trim(eregi_replace('from','from',$this->sql));
			$this->sql=$this->sql;
			$sql='select count(*) as _all '.substr($this->sql,strpos($this->sql,'from'));

			 if(strpos($sql,'order by'))
			  {
					$a=explode('order by',$sql);
					$sql=$a[0];
			  }
			  	//echo "dfff".$sql;

		//	$sql=substr($this->sql,strpos($this->sql,'from'));
//			if(strpos($sql,'order by')){
//				$sql=substr($this->sql,strpos($this->sql,'order by'));
//			}else{
//				$sql='select count(*) as _all '.substr($this->sql,strpos($this->sql,'from'));
//			}

			//echo $sql.strpos($sql,'order by')."<br/>";
			$result=mysql_query($sql,$this->link);
			$rs=$this->fetch_array($result);
			$this->count=$rs["_all"];
		}
		return $this->count;
	 }//end function

	 //get the max page;
	 public function maxpage()
	 {
	 	if(!$this->maxpage){
			if($this->recordcount() % $this->pagesize==0)
			{
				$this->maxpage=($this->recordcount()/$this->pagesize);
			}
			else
			{
				$this->maxpage=intval($this->recordcount()/$this->pagesize)+1;
			}
		}
		return $this->maxpage;
	 }//end function

	 public function currentpage()
	 {
	 	$this->page=!empty($_REQUEST[$this->pagename])?intval($_REQUEST[$this->pagename]):1;
			$this->page=$this->page>$this->maxpage()?$this->maxpage():$this->page;
			$this->page=$this->page<1?1:$this->page;
		return $this->page;
	 }//end function

	//fetch array from connection
	public function fetch_array($query=0, $result_type = MYSQL_ASSOC) {
		if(!$query){
			if($this->result!='' && $this->result!=NULL)
				return mysql_fetch_array($this->result,$result_type);
		}else{
			return mysql_fetch_array($query, $result_type);
		}
	}//end function

	function query($sql, $type='')
	{
			$func = $type == 'UNBUFFERED' ? 'mysql_unbuffered_query' : 'mysql_query';
			if(!($querys = @$func($sql , $this->link)) && $type != 'SLIENT')
			{
				$this->halt('MySQL Query Error', $sql);
				return false;
			}
			return $querys;
	}

	function insert($tablename, $array)
	{
		    $sql = "INSERT INTO $tablename(".implode(',', array_keys($array)).") VALUES('".implode("','", $array)."')";
			//die($sql);
			if ($this->query($sql))
			{
			    return $this->insertId();

			}
			else
			{
			    return false;
			}
	}

	function getOne($sql, $limited = false)//获得单条记录
    {
	//die($sql);
        	if ($limited == true)
        	{
            	$sql = trim($sql . ' LIMIT 1');
        	}
        	$res = $this->query($sql);
        	if ($res !== false)
        	{
            	$row = mysql_fetch_row($res);//mysql_fetch_row不会合并相同记录

            	if ($row !== false)
            	{
					return $row[0];
           	 	}
            	else
            	{
                	return '';
            	}
        	}
        	else
        	{
            	return '';
        	}
   }
    //获取所有数据填充到数组
	  function getAll($sql)
	  {
	     $res = $this->query($sql);
		 if ($res !== false)
		 {
		    $arr = array();
			while($row = mysql_fetch_assoc($res))
			{
			   $arr[] = $row;
			}
			return $arr;
		 }
		 else
		 {
		    return array();
		 }
	  }

	   //获取单行记录填充到数组
	  function getRow($sql, $limit = false)
	  {
	      if($limit == true)
		  {
		     $sql =trim($sql . ' LIMIT 1');
		  }
		  $res = $this->query($sql);
          if ($res !== false)
		  {
		     return mysql_fetch_assoc($res);
		  }
		  else
		  {
		     return array();
		  }

	  }

	 function fetchArray($query, $result_type = MYSQL_ASSOC)
	 {
		return mysql_fetch_array($query, $result_type);
	 }

	function insertId()
	{
		return mysql_insert_id($this->link);
	}
	function update($tablename, $array, $where = '')
	{
			if($where)
			{
				$sql = '';
				foreach($array as $k=>$v)
				{
					$sql .= ", $k ='$v'";
				}
				$sql = substr($sql, 1);
				$sql = "UPDATE $tablename SET $sql WHERE $where";
			}
			else
			{
				$sql = "REPLACE INTO $tablename(".implode(',', array_keys($array)).") VALUES('".implode("','", $array)."')";
			}
			//die($sql);
			return $this->query($sql);
	}
	//free result
	public function free_result($result=null){
		if($result==null){
			if(is_resource($this->result))
				mysql_free_result($this->result);
		}
		else{
			mysql_free_result($result);
		}
	}//end function

	//close mysql
	public function close() {
		if(is_resource($this->link))
			return mysql_close($this->link);
	}//end function

	//return error information
	public function halt($message = '', $sql = '') {
		exit($message.'<br /><br />'.$sql.'<br /> '.mysql_error());
	}//end function

	/*
	protected function getUrl()
	{


		if(!$this->url)
		{
			$url=$_SERVER['REQUEST_URI'];
			$this->url=strpos($url,'?')?$url:$url.'?';
			$this->url=str_replace('&'.$this->pagename.'='.$_GET[$this->pagename],'',$this->url);
		}
		return $this->url;
	}//end function
	*/

	protected function getUrl()
	{
		if(!$this->url)
		{
			$url=$_SERVER['REQUEST_URI'];
			$this->url=strpos($url,'?')?$url:$url.'?';
			$_REQUEST[$this->pagename] = isset($_REQUEST[$this->pagename]) ? $_REQUEST[$this->pagename] : 1;
			$this->url=str_replace('&'.$this->pagename.'='.$_REQUEST[$this->pagename],'',$this->url);
		}
		return $this->url;
	}//end function


	//the url must contain the symbol of "$$"; $$ will be replaced by page num;Chinese;
	public function pageindex($url)
	{

		$strpage='';
		$strpage.='<div class="pages">';
		$strpage.=' 总记录数:'.$this->recordcount().';';
		$strpage.=' 总页数:'.$this->maxpage().';';
		$strpage.=" 每页$this->pagesize 条".';';
		$strpage.=' <a href="'.str_replace('$$','1',$url).'" title="首页">首页</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" title="上一页">上一页</a> ';

		//get startpage and endpage
		$startPage=$this->page-4;
		$endPage=$this->page+5;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=10;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-9;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<10)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.=$i.'&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).'" title="第'.$i.'页">['.$i.']</a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'" title="下一页">下一页</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).'" title="首页">尾页</a></div>';
		return $strpage;
	}//end function
	//the url must contain the symbol of "$$"; $$ will be replaced by page num;Chinese;
	public function pageindexs($url)
	{
		$strpage='';
		$strpage.='<div class="paging">';
		//$strpage.=' 总页数:'.$this->maxpage().';';
		//$strpage.=' <a href="'.str_replace('$$','1',$url).'" title="首页">首页</a>';
		//$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" title="上一页">上一页</a> ';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" class="previous"><span class="Bg"><b>&nbsp;</b></span></a> ';

		//get startpage and endpage
		$startPage=$this->page-4;
		$endPage=$this->page+5;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=10;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-9;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<10)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.='<a class="cur" ><span class="Bg"><b>'.$i.'</b></span></a>&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).'" title="第'.$i.'页"><span class="Bg"><b>'.$i.'</b></span></a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'"  class="nextpage" title="下一页"><span class="Bg"><b>&nbsp;</b></span></a>';
		//$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'" title="下一页">下一页</a>';
		//$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).'" title="首页">尾页</a></div>';
		return $strpage;
	}//end function

   public function pageindexes($url)
	{
				$strpage='';
		$strpage.='<div class="pages">';
/*		$strpage.=' <a href="'.str_replace('$$','1',$url).'" title="首页">首页</a>';
*/		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).'" title="上一页">上一页</a> ';

		//get startpage and endpage
		$startPage=$this->page-4;
		$endPage=$this->page+5;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=10;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-9;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<10)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
/*				$strpage.='<a class="atpage" style="color:#F00; font-size:20px; font-weight:bold; padding-top:0px; border:0px;">'.$i.'</a>&nbsp;';
*/			}
			else
			{
/*				$strpage.='<a href="'.str_replace('$$',$i,$url).'" title="第'.$i.'页">'.$i.'</a>&nbsp;';
*/			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).'" title="下一页">下一页</a>';
/*		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).'" title="首页">尾页</a></div>';
*/		return $strpage;
	}//end function
	//the url must contain the symbol of "$$"; $$ will be replaced by page num;Chinese;
	public function pageindex_mobile($url)
	{


	    $strpage = '';
		$strpage.="<div class='list_page'>";
		if($this->page==1){
			$strpage.="<div class='disabled' title='已经是第一页'>上一页</div>";
		}else{
			$strpage.="<div class=' '><a href='".str_replace('%',$this->page-1,$url)."' title='上一页'>上一页</a></div>";
		}
		$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
		if($this->page>=$this->maxpage()){
			$strpage.="<div class='disabled' title='已经是最后一页'>下一页</div>";
		}else{
			$strpage.="<div class=' '><a href='".str_replace('%',$this->page+1,$url)."' title='下一页'>下一页</a></div>";
		}
		$strpage.="</div>";

		return $strpage;
	}//end function


	public function pageindex_shop($url)
	{


	    $strpage = '';
		$strpage.="<div class='page_a' style='text-align: center;'>";
		if($this->page==1){
			if($this->maxpage()>$this->page){ //有下一页
			$strpage.="<a class='ll' href='".str_replace('%',$this->page+1,$url)."' style='width: 89%; display: inline-block; float: none;' title='下一页'>下一页</a>";
			}
		}else{
			if($this->page<>$this->maxpage()){
				$strpage.="<a class='ll' href='".str_replace('%',$this->page-1,$url)."' title='上一页'>上一页</a>";
			}
		}
		//$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
		if($this->page>=$this->maxpage()){
			if($this->maxpage()>$this->page){ //有下一页
			 $strpage.="<a class='rr' href='".str_replace('%',$this->page+1,$url)."' style='width: 89%; display: inline-block; float: none;' title='下一页'>下一页</a>";
			}else{
			 $strpage.="<a class='ll' href='".str_replace('%',$this->page-1,$url)."' style='width: 89%; display: inline-block; float: none;' title='上一页'>上一页</a>";
			}
		}else{
			if($this->page<>1){
				$strpage.="<a class='rr' href='".str_replace('%',$this->page+1,$url)."' title='下一页'>下一页</a>";
			}
		}
		$strpage.="</div>";

		return $strpage;
	}//end function

	public function pageindex_mobile_news($url)
	{
		// <div class="pageer">
                          //  <div class="page_left"><a href="#"><img src="images/page_left.png"></a></div>
                            // <div class="page_right"><a href="#"><img src="images/page_right.png"></a></div>
							 //</div>

	    $strpage = '';
		$strpage.="<div class='pageer'>";
		if($this->page==1){
			$strpage.="<div class='page_left' title='已经是第一页'><img src='/template/show_02/images/page_left.png'></div>";
		}else{
			$strpage.="<div class='page_left'><a href='".str_replace('%',$this->page-1,$url)."' title='上一页'><img src='/template/show_02/images/page_left.png'></a></div>";
		}
		//$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
		if($this->page>=$this->maxpage()){
			$strpage.="<div class='page_right' title='已经是最后一页'><img src'/template/show_02/images/page_right.png'></div>";
		}else{
			$strpage.="<div class='page_right'><a href='".str_replace('%',$this->page+1,$url)."' title='下一页'><img src'/template/show_02/images/page_right.png'></a></div>";
		}
		$strpage.="</div>";

		return $strpage;
	}//end function


	public function pageindex_new($url)
	{
// <div class="list_page">
//           <div class=" disabled ">上一页</div>
//           <div class="allpage"><div class="currentpage">1/2</div>
//           </div>
//           <div class=" "> <a href="news.shtml?id=156&amp;nid=&amp;page=2">下一页</a></div>
//      </div>
//
//</div>


	    $strpage = '';
		$strpage.="<div style='float:right;padding-right:10px;'>";
		if($this->page==1){
			$strpage.="<img title='已经是第一页' src='/members/images/page-1.png' />&nbsp;&nbsp;&nbsp;";
		}else{
			$strpage.="<a href='".str_replace('%',$this->page-1,$url)."' title='上一页'><img src='/members/images/page-1.png' /></a>&nbsp;&nbsp;&nbsp;";
		}
		//$strpage.="<div class='allpage'><div class='currentpage'>".$this->page."/".$this->maxpage()."</div></div>";
//		for($i=$startPage;$i<=$endPage;$i++)
//		{
//			if($i==$this->page)
//			{
//				$strpage.=$this->page."/".$this->maxpage();
//			}
//			else
//			{
//				$strpage.=$this->page."/".$this->maxpage();
//			}
//		}
		if($this->page==$this->maxpage()){
			$strpage.="<img title='已经是最后一页' src='/members/images/page-2.png' />";
		}else{
			$strpage.="<a href='".str_replace('%',$this->page+1,$url)."' title='下一页'><img src='/members/images/page-2.png' /></a>";
		}
		$strpage.="</div>";

		return $strpage;
	}//end function


	//the url must contain the symbol of "$$"; $$ will be replaced by page num. English;
	public function pageEnglishIndex($url,$anchor='')
	{
		if($anchor!='')
			$anchor="#".$anchor;

		$strpage='';
		$strpage.= '<div class="luotong_page">';
		$strpage.=' Total:'.$this->recordcount().';';
		$strpage.=' Total Pages:'.$this->maxpage().';';
		$strpage.=" One Page:$this->pagesize".';';
		$strpage.=' <a href="'.str_replace('$$','1',$url).$anchor.'" title="First Page">First</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).$anchor.'" title="Previous Page">Previous</a> </div>';
		//get startpage and endpage
		$startPage=$this->page-3;
		$endPage=$this->page+3;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=7;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-6;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<7)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.=$i.'&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).$anchor.'" title="'.$i.'">['.$i.']</a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).$anchor.'" title="Next Page">Next</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).$anchor.'" title="End Page">End</a></div>';
		return $strpage;
	}//end function
	//the url must contain the symbol of "$$"; $$ will be replaced by page num. English;
	public function pageEnglishIndexs($url,$anchor='')
	{
		if($anchor!='')
			$anchor="#".$anchor;

		$strpage='';
		$strpage.= '<div class="luotong_page">';
		$strpage.=' Total:'.$this->recordcount().';';
		$strpage.=' Total Pages:'.$this->maxpage().';';
		$strpage.=" One Page:$this->pagesize".';';
		$strpage.=' <a href="'.str_replace('$$','1',$url).$anchor.'" title="First Page">First</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->page-1,$url).$anchor.'" title="Previous Page">Previous</a> </div>';
		//get startpage and endpage
		$startPage=$this->page-3;
		$endPage=$this->page+3;
		if($startPage<1)
		{
			$startPage=1;
			$endPage=7;
		}
		if($endPage>$this->maxpage)
		{
			$startPage=$this->maxpage-6;
			$endPage=$this->maxpage;
		}
		if($this->maxpage<7)
		{
			$startPage=1;
			$endPage=$this->maxpage;
		}

		//show the index of pages
		for($i=$startPage;$i<=$endPage;$i++)
		{
			if($i==$this->page)
			{
				$strpage.=$i.'&nbsp;';
			}
			else
			{
				$strpage.='<a href="'.str_replace('$$',$i,$url).$anchor.'" title="'.$i.'">['.$i.']</a>&nbsp;';
			}
		}
		$strpage.=' <a href="'.str_replace('$$',$this->page+1,$url).$anchor.'" title="Next Page">Next</a>';
		$strpage.=' <a href="'.str_replace('$$',$this->maxpage(),$url).$anchor.'" title="End Page">End</a></div>';
		return $strpage;
	}//end function

	public function showpage($language=null,$anchor='')
	{
		echo $this->strpage($language,$anchor);

	}//end function
	public function showpages($language=null,$anchor='')
	{
		echo $this->strpages($language,$anchor);

	}//end function
	public function showpagees($language=null,$anchor='')
	{
		echo $this->strpagees($language,$anchor);

	}//end function

	public function strpage($language=null,$anchor=''){
		if($this->pagesize){
			switch($language)
			{
				case 'English':
					return $this->pageEnglishIndex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
				default:
					return $this->pageindex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
			}
		}
	}
	public function strpages($language=null,$anchor=''){
		if($this->pagesize){
			switch($language)
			{
				case 'English':
					return $this->pageEnglishIndex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
				default:
					return $this->pageindexs($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
			}
		}
	}

	public function strpagees($language=null,$anchor=''){
		if($this->pagesize){
			switch($language)
			{
				case 'English':
					return $this->pageEnglishIndex($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
				default:
					return $this->pageindexes($this->getUrl().'&'.$this->pagename.'=$$',$anchor);
					break;
			}
		}
	}

	//得到表的字段名，并返回字段名数组
	function getCol($DESC){
        $res = mysql_query($DESC,$this->link);
        if ($res !== false){
            $arr = array();
            while ($row = mysql_fetch_row($res))
            {
                $arr[] = $row[0];
            }

            return $arr;
        }
        else
        {
            return false;
        }
    }//end function

//将表单数组数据更新到数据库,$table:表名，$field_array：表单值数组；$mode:操作开关，默认为insert;where，查询语句
  	function autoExecute($table,$field_array,$mode='insert',$where=''){
		$field_names=$this->getCol(' DESC '.$table);
		$sql = '';
		switch($mode){
	  	case 'insert':
	   		$fields=$values=array();
	   		foreach($field_names as $value){
	   			if(array_key_exists($value,$field_array)==true && $field_array[$value]!='')
	   			{
	     			$fields[]=$value;
		 			$values[]="'".addslashes($field_array[$value])."'";
	   			}
	  		}
			if (!empty($fields))
       		{
        		$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
       		}
			//die($sql);
			break;
    	case 'update':
	  		$sets = array();
            foreach ($field_names as $value)
            {
                if (array_key_exists($value, $field_array) == true)
                {
                    $sets[] = $value . " = '" . addslashes($field_array[$value]) . "'";
                }
            }

            if (!empty($sets))
            {
                $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
				//echo $sql;
            }
	  		break;
   		}
   		if ($sql)
        {
            mysql_query($sql,$this->link);
			return mysql_insert_id();
        }
        else
        {
            return false;
        }
  	}//end function


	public function __destruct(){
		$this->free_result();
		$this->close();
	}//end function
}//end class
?>
