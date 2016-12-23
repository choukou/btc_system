<?php
class Model_Paging {
	public function __construct() {
		//content
	}
	public function pagination($urlBase=null, $page, $recordCount, $pageLimit,$admin=1){
		$pagesToShow	=	9;
		$pageCount 		= 	floor($recordCount / $pageLimit);
		$pages 			= 	array();
		$lowerCount		=	($pagesToShow - 1) / 2;
		$higherCount 	= 	($pagesToShow - 1) / 2;
		$textPage		=	'';
		// $pageToShow must be odd number
		if($pagesToShow % 2		==	0){
			$pagesToShow++;
		}
		// Calculate total no of pages
		if($recordCount % $pageLimit !=	0){
			$pageCount++;
		}
		// Check if request page exceeds the no of pages
		if($page!= 1 && $page > $pageCount){
			$page	=	1;
		}
		if($pageCount <= $pagesToShow){
			for($i = 1; $i <= $pageCount; $i++){
				$pages[$i]	=	$i;
			}
		}else{
			if($page < ($pagesToShow + 1) / 2){
				for($i = 0; $i < ($pagesToShow + 1) / 2 - $page; $i++){
					$lowerCount--;
					$higherCount++;
				}
			}else if($pageCount - $page < ($pagesToShow - 1) / 2){
				for($i = 0; $i < ($pagesToShow - 1) / 2 - $pageCount + $page; $i++){
					$lowerCount++;
					$higherCount--;
				}
			}
			for($i = 1; $i <= $lowerCount; $i++){
				$pages[$lowerCount - $i + 1] = $page - $i;
			}
			$pages[$lowerCount + 1] = $page;
			for($i = 1; $i <= $higherCount; $i++){
				$pages[$lowerCount + $i + 1] = $page + $i;
			}
		}
		$pages['prev'] = max(1, $page - 1); 			// previous page number
		$pages['next'] = min($page + 1, $pageCount); 	// next page number
		$textPage	=	'<div class="w-paging"><ul class="paging pagination pull-right">';
		if($page>2){
			$textPage	.=	'<li class="page-number page-first" onclick="loadTable(1)" style="margin-right:4px;cursor: pointer;"><a><<</a></li>';
		}
		if ($page != 1){
			$textPage	.=	'<li class="page-number" onclick="loadTable('.$pages['prev'].')" style="margin-right:4px;cursor: pointer;"><a><</a></li>';
		}
		for($i = 1; $i <= $pagesToShow; $i++){
			if(isset($pages[$i]) && $pages[$i] == $page){
					$textPage	.=	'<li class="page-number active" onclick="loadTable('.$pages[$i].')" style="margin-right:4px;cursor: pointer;">';
					$textPage	.=	'<a>';
					$textPage	.=	$pages[$i];
					$textPage	.=	'</a>';
					$textPage	.=	'</li>';
			}elseif(isset($pages[$i]) && $pages[$i] != ''){
					$textPage	.=	'<li class="page-number" onclick="loadTable('.$pages[$i].')" style="margin-right:4px;cursor: pointer;">';
					$textPage	.=	'<a>';
					$textPage	.=	$pages[$i];
					$textPage	.=	'</a>';
					$textPage	.=	'</li>';
			}
		}
		if ($page != $pageCount){
			$textPage	.=	'<li class="page-number"  onclick="loadTable('.$pages['next'].')" style="margin-right:4px;cursor: pointer;"><a>></a></li>';
		}
		if($page<$pageCount-1){
			$textPage	.=	'<li class="page-number page-last" onclick="loadTable('.$pageCount.')" style="margin-right:4px;cursor: pointer;"><a>>></a></li>';
		}
		$textPage	.=	'</ul><div class="clearall"></div></div>';
		if($admin==1){
			$checkAll	=	'<script type="text/javascript">checkbox_All();</script>';
			echo $checkAll;
		}
		//return result
		return $textPage;
	}

	public function show($urlBase=null, $page, $recordCount, $pageLimit,$admin=1){
		$pagesToShow	=	9;
		$pageCount 		= 	floor($recordCount / $pageLimit);
		$pages 			= 	array();
		$lowerCount		=	($pagesToShow - 1) / 2;
		$higherCount 	= 	($pagesToShow - 1) / 2;
		$textPage		=	'';
		// $pageToShow must be odd number
		if($pagesToShow % 2		==	0){
			$pagesToShow++;
		}
		// Calculate total no of pages
		if($recordCount % $pageLimit !=	0){
			$pageCount++;
		}
		// Check if request page exceeds the no of pages
		if($page!= 1 && $page > $pageCount){
			$page	=	1;
		}
		if($pageCount <= $pagesToShow){
			for($i = 1; $i <= $pageCount; $i++){
				$pages[$i]	=	$i;
			}
		}else{
			if($page < ($pagesToShow + 1) / 2){
				for($i = 0; $i < ($pagesToShow + 1) / 2 - $page; $i++){
					$lowerCount--;
					$higherCount++;
				}
			}else if($pageCount - $page < ($pagesToShow - 1) / 2){
				for($i = 0; $i < ($pagesToShow - 1) / 2 - $pageCount + $page; $i++){
					$lowerCount++;
					$higherCount--;
				}
			}
			for($i = 1; $i <= $lowerCount; $i++){
				$pages[$lowerCount - $i + 1] = $page - $i;
			}
			$pages[$lowerCount + 1] = $page;
			for($i = 1; $i <= $higherCount; $i++){
				$pages[$lowerCount + $i + 1] = $page + $i;
			}
		}


		$pages['prev'] = max(1, $page - 1); 			// previous page number
		$pages['next'] = min($page + 1, $pageCount); 	// next page number
		$textPage	=	'<ul class="pagination">';
		if($page>2){
			$textPage   .=  '<li class="page-number page-first" onclick="loadTable(1)" style="margin-right:4px;cursor: pointer;"><a><<</a></li>';
		}
		if ($page != 1){
			$textPage	.=	'<li><a class="prevpostslink" href="#"><span>&larr;</span> Previous</a></li>';
		}
		for($i = 1; $i <= $pagesToShow; $i++){
			if(isset($pages[$i]) && $pages[$i] == $page){
					$textPage	.=	'<li class="current">';
					$textPage	.=	'<a>';
					$textPage	.=	$pages[$i];
					$textPage	.=	'</a>';
					$textPage	.=	'</li>';
			}elseif(isset($pages[$i]) && $pages[$i] != ''){
					$textPage	.=	'<li class="">';
					$textPage	.=	'<a>';
					$textPage	.=	$pages[$i];
					$textPage	.=	'</a>';
					$textPage	.=	'</li>';
			}
		}
		if ($page != $pageCount){
			$textPage .= '<li><a class="nextpostslink" href="#">Next <span>&rarr;</span></a></li>';
		}
		if($page<$pageCount-1){
			$textPage	.=	'<li class="page-last" onclick="loadTable('.$pageCount.')" style="margin-right:4px;cursor: pointer;"><a>>></a></li>';
		}

		$textPage .=	'</ul>';
		return $textPage;
	}


}
