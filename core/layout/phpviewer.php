<?php
/**
 * LibreCMS - Copyright (C) Diemen Design 2019
 *
 * Administration - Project Honey Pot IP Information Viewer
 *
 * phpviewer.php version 2.0.2
 *
 * LICENSE: This source file may be modifired and distributed under the terms of
 * the MIT license that is available through the world-wide-web at the following
 * URI: http://opensource.org/licenses/MIT.  If you did not receive a copy of
 * the MIT License and are unable to obtain it through the web, please
 * check the root folder of the project for a copy.
 *
 * @category   Administration - Popup - PHPViewer
 * @package    core/layout/phpviewer.php
 * @author     Dennis Suitters <dennis@diemen.design>
 * @copyright  2014-2019 Diemen Design
 * @license    http://opensource.org/licenses/MIT  MIT License
 * @version    2.0.2
 * @link       https://github.com/DiemenDesign/LibreCMS
 * @notes      This PHP Script is designed to be executed using PHP 7+
 * @changes    v2.0.2 Add i18n.
 * @changes    v2.0.2 Fix ARIA Attributes.
 */
if(!defined('DS'))define('DS',DIRECTORY_SEPARATOR);
$getcfg=true;
require'..'.DS.'db.php';
include'..'.DS.'class.projecthoneypot.php';
$idh=time();
echo'<div id="phpviewer'.$idh.'">';
if(!isset($config['php_APIkey'])||$config['php_APIkey']==''){
  echo'<div class="alert alert-info" role="alert">'.localize('alert_php_info_nokey').'</div>';
}else{
  function svg($svg,$class=null,$size=null){
  	echo'<i class="libre'.($size!=null?' libre-'.$size:'').($class!=null?' '.$class:'').'">'.file_get_contents('..'.'..'.DS.'svg'.DS.$svg.'.svg').'</i>';
  }
  function svg2($svg,$class=null,$size=null){
  	return'<i class="libre'.($size!=null?' libre-'.$size:'').($class!=null?' '.$class:'').'">'.file_get_contents('..'.DS.'svg'.DS.$svg.'.svg').'</i>';
  }
  $id=isset($_POST['id'])?filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT):filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
  $t=isset($_POST['t'])?filter_input(INPUT_POST,'t',FILTER_SANITIZE_STRING):filter_input(INPUT_GET,'t',FILTER_SANITIZE_STRING);
  define('URL', PROTOCOL.$_SERVER['HTTP_HOST'].$settings['system']['url'].'/');
  define('UNICODE','UTF-8');
  if($t=='comments')
    $s=$db->prepare("SELECT ip FROM `".$prefix."comments` WHERE id=:id");
  else
    $s=$db->prepare("SELECT ip FROM `".$prefix."tracker` WHERE id=:id");
  $s->execute([':id'=>$id]);
  if($s->rowCount()>0){
    $r=$s->fetch(PDO::FETCH_ASSOC);
    if(filter_var($r['ip'],FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
      $h=new ProjectHoneyPot($r['ip'],$config['php_APIkey']);
      if($h->hasRecord()==1){
        if($h->isSuspicious()==1){
          echo'<strong>'.$r['ip'].'</strong> ',localize('php_listed');
          if($h->isCommentSpammer()==1)echo localize('php_commentspammer');
          if($h->isHarvester()==1)echo localize('php_harvester');
          if($h->isSearchEngine()==1)echo localize('php_searchengine').'.<br>';else echo'.<br>';
          if($h->getThreatScore()>0)echo localize('php_threatscore').' <strong>'.$h->getThreatScore().'</strong> <a target="_blank" href="https://www.projecthoneypot.org/threat_info.php" data-tooltip="tooltip" title="'.localize('php_linktooltip').'">?</a>.';
        }
      }else
        echo localize('php_norecords').'...';
      $sql=$db->prepare("SELECT COUNT(id) as cnt FROM `".$prefix."iplist` WHERE ip=:ip");
      $sql->execute([':ip'=>$r['ip']]);
      $row=$sql->fetch(PDO::FETCH_ASSOC);
      if($row['cnt']<1){?>
  <div id="phpbuttons" class="btn-group pull-right" role="group">
    <form id="blacklist<?php echo$idh;?>" method="post" action="core/add_blacklist.php" role='form'>
      <input type="hidden" name="id" value="<?php echo$id;?>">
      <input type="hidden" name="t" value="<?php echo$t;?>">
      <button class="btn btn-secondary btn-xs" data-tooltip="tooltip" title="<?php echo localize('php_addtooltip');?>" role="button" aria-label="<?php echo localize('aria_add');?>"><?php echo svg2('libre-gui-security');?></button>
    </form>
  </div>
  </div>
  <script>
    $("#blacklist<?php echo$idh;?>").submit(function(){
        $.post($(this).attr("action"),$(this).serialize(),function(data){
          $('#phpviewer<?php echo$idh;?>').html(data);
        });
        return false;
    });
<?php if($config['options']{4}==1){?>
        $('[data-tooltip="tooltip"]').tooltip();
<?php }?>
  </script>
<?php }
    }else
      echo localize('php_ipinvalid');
  }else
    echo localize('No Results Found.');
}
