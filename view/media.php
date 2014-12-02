<?php
$content.='<main id="content" class="col-md-12">';
	$content.='<div class="panel panel-default">';
		$content.='<div class="panel-body">';
if($user['rank']>900){
			$content.='<div class="btn-add col-md-4">';
				$content.='<form method="post" target="sp" enctype="multipart/form-data" action="includes/add_data.php">';
					$content.='<div class="form-group">';
						$content.='<div class="input-group">';
							$content.='<input type="file" name="file[]" class="form-control" multiple data-classButton="btn btn-success" data-input="false">';
							$content.='<input type="hidden" name="act" value="add_media">';
							$content.='<div class="input-group-btn">';
								$content.='<button class="btn btn-success" onclick="$(\'#block\').css({\'display\':\'block\'});">Upload</button>';
							$content.='</div>';
						$content.='</div>';
					$content.='</div>';
				$content.='</form>';
			$content.='</div>';
			$content.='<ul id="media" class="sortable grid">';
	$path='media/';
	$upload_dir='media/';
	$handle=opendir($upload_dir);
	while($file=readdir($handle)){
		if(!is_dir($upload_dir.$file)&&!is_link($upload_dir.$file)){
			$docs[]=$file;
		}
	}
	if(isset($docs)){
		sort($docs);
		$i=0;
		foreach($docs as $key=>$file){
			$finfo=new finfo(FILEINFO_MIME_TYPE);
			$type=$finfo->file('media/'.$file);
			$img='<span class="filetype img-thumbnail"><i class="fa fa-file-o fa-5x"></i></span>';
			if($type=='application/msword'||$type=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'||$type=='application/vnd.openxmlformats-officedocument.wordprocessingml.template'||$type=='application/rtf'||$type=='application/x-rtf'||$type=='text/richtext'||$type=='application/rtf'||$type=='text/richtext'){
				$img='<span class="filetype img-thumbnail"><i class="fa fa-word-pdf-o fa-5x"></i></span>';
			}elseif($type=='audio/mpeg3'||$type=='audio/x-mpeg-3'){
				$img='<span class="filetype img-thumbnail"><i class="fa fa-file-audio-o fa-5x"></i></span>';
			}elseif($type=='application/x-troff-msvideo'||$type=='video/avi'||$type=='video/msvideo'||$type=='video/x-msvideo'||$type=='video/mp4'||$type=='video/mpeg'||$type=='audio/mpeg'){
				$img='<span class="filetype img-thumbnail"><i class="fa fa-file-video-o fa-5x"></i></span>';
			}elseif($type=='application/pdf'){
				$img='<span class="filetype img-thumbnail"><i class="fa fa-file-pdf-o fa-5x"></i></span>';
			}elseif($type=='application/x-rar-compressed'||$type=='application/x-compressed'||$type=='application/x-zip-compressed'||$type=='application/zip'||$type=='multipart/x-zip'||$type=='application/gnutar'||$type=='application/x-compressed'){
				$img='<span class="filetype img-thumbnail"><i class="fa fa-archive-pdf-o fa-5x"></i></span>';
			}elseif($type=='text/plain'){
				$img='<span class="filetype img-thumbnail"><i class="fa fa-text-pdf-o fa-5x"></i></span>';
			}else{
				$img='<a title="'.$file.'" href="media/'.$file.'" data-lightbox-gallery="'.$view.'" rel="'.$view.'"><img src="media/'.$file.'" class="img-thumbnail"></a>';
			}
				$content.='<li id="l_'.str_replace('.','',$file).'" class="gallery">';
					$content.=$img;
					$content.='<div id="controls_'.str_replace('.','',$file).'" class="controls">';
						$content.='<button class="btn btn-danger btn-xs" onclick="removeMedia(\''.$file.'\')"><i class="fa fa-trash"></i></button> ';
					$content.='</div>';
					$content.='<div class="title">'.$file.'</div>';
				$content.='</li>';
		}
			$content.='</ul>';
		$content.='</div>';
	}
}else{
	include'includes/noaccess.php';
}
		$content.='</div>';
	$content.='</div>';
$content.='</main>';