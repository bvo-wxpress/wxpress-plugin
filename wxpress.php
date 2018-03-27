<?php
error_reporting(E_ALL & ~E_WARNING);

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
require plugin_dir_path(__FILE__)."aws-autoloader.php";

function out($foo){
  echo "<pre>";
  print_r($foo);
  echo "</pre>";
}

class s3Export{
  //TODO change bucket name
  var $bucket = 'bvo-wxpress3';
  var $exportURL = 'http://bvo-wxpress3.s3-website-us-east-1.amazonaws.com';
  var $homeDir = '';
  var $themeDir = '';
  var $siteHost = '';
  var $exportDirs = [];
  var $exports = [];
  var $pageExports = [];
  var $s3Client = null;

  function __construct($urls){
    $this->urls = $urls;
    $this->homeDir = get_home_path();
    $this->exportDirs = [
      'wp-includes/css/',
      'wp-includes/js/',
      'wp-content/uploads/',
    ];
    $this->siteHost = site_url();
    $templateDir = get_template_directory();
    $themeDir = str_replace($this->homeDir,'',$templateDir);
    $this->themeDir = $themeDir;
    $this->exportDirs[] = $themeDir.DIRECTORY_SEPARATOR;
    $s3Client = new S3Client([
      'version'     => 'latest',
      'region'      => 'us-east-1',
    ]);
    $this->s3Client = $s3Client;
  }

  public function sendAssets(){
    $this->prepareSite();
    foreach ($this->exportDirs as $key => $exportDir) {
      $this->dirToArray($exportDir);
    }
    foreach ($this->exports as $key => $export) {
      if ($export['ext'] == 'php') {
        continue;
      }
      // out($export);
      $this->sendToS3($export['local'],$export['s3']);
    }
  }

  public function dirToArray($dir,$base = '') {
    if (!$base) {
      $base = $this->homeDir;
    }
    $result = array();
    $cdir = scandir($base.$dir);
    foreach ($cdir as $key => $value) {
      if (!in_array($value,array(".",".."))) {
        $checkDir = $base . $dir . DIRECTORY_SEPARATOR . $value;
        if (is_dir($checkDir)) {
          $result[$value] = $this->dirToArray($dir.$value.DIRECTORY_SEPARATOR,$base);
        }else {
          $s3 = $dir.$value;
          $local = $base.$dir.$value;
          $ext = pathinfo($local, PATHINFO_EXTENSION);
          $r = [
            's3' => $s3,
            'local' => $local,
            'ext' => $ext,
            'filename' => basename($s3)
          ];
          $result[] = $r;
          $this->exports[] = $r;
        }
      }
    }
    return $result;
  }

  public function sendToS3($file, $key){
    // return true;
    $s3Client = $this->s3Client;
    if (strlen($key) > 1) {
      $key = ltrim($key,'/');
    }
    out("key: " . $key);
    out('file: '.$file);
    out("bucket: ". $this->bucket);
    try {
      $success = null;
      if ($file) {
        out($key);
        $success = $s3Client->putObject([
          'Bucket' => $this->bucket,
          'Key' => $key,
          'Body' => '',
          'SourceFile' => $file,
          'ACL' => 'public-read'
        ]);
      }else{
        $success = $s3Client->putObject([
          'Bucket' => $this->bucket,
          'Key' => $key,
          'Body' => '',
          'ACL' => 'public-read'
        ]);
      }

      out($success);
    } catch (S3Exception $e) {
        // Catch an S3 specific exception.
        echo $e->getMessage();
        die();
    } catch (AwsException $e) {
        // This catches the more generic AwsException. You can grab information
        // from the exception using methods of the exception object.
        echo $e->getAwsRequestId() . "\n";
        echo $e->getAwsErrorType() . "\n";
        echo $e->getAwsErrorCode() . "\n";
        die();
    }
  }
  public function insertCDN(&$html){
    $hrefTags = [
      'a',
      'link'
    ];
    $srcTags = [
      'img',

    ];
    $tags = [
      'href' => ['a','link'],
      'src'  => ['img','script']
    ];
    // die($this->exportURL);
    $html = str_replace($this->siteHost,$this->exportURL,$html);
    $html = str_replace(urlencode($this->siteHost),urlencode($this->exportURL),$html);

    $slashedSiteHost = str_replace('/','\/',$this->siteHost);
    $slashedExportUrl = str_replace('/','\/',$this->exportURL);
    $html = str_replace($slashedSiteHost,$slashedExportUrl,$html);

    // $anchors = $doc->getElementsByTagName('a');
    // foreach ($anchors as $key => $a) {
    //   // out($s->getAttribute('src'));
    //   $href = $a->getAttribute('href');
    //   $parse_href = parse_url($href);
    //   if (isset($parse_href['host'])) {
    //     if ($parse_href['host'] == $this->siteHost) {
    //       out('href: '.$href);
    //       out($parse_href);
    //       $a->setAttribute('href',$parse_href['path']);
    //       //change url
    //     }
    //   }
    // }
  }
  public function prepareSite(){

    // update_option( 'siteurl', 'http://bvo-wxpress3.s3-website-us-east-1.amazonaws.com/' );
    // update_option( 'home', 'http://bvo-wxpress3.s3-website-us-east-1.amazonaws.com/' );
    $urls = $this->urls;
    $rootdir = plugin_dir_path(__FILE__)."export/".time();
    mkdir($rootdir);
    $assets = array();
    foreach ($urls as $key => $url) {
      out($url);
      $fileLocation = $rootdir;
      $p = parse_url($url);
      $s3Path = $p['path'];
      $fileLocation .= $s3Path;
      $html = file_get_contents($url);
      if (!strlen($html)) {
        die("could not get html");
      }
      $this->insertCDN($html);
      $doc = new DOMDocument();
      $doc->loadHTML($html);
      $filePath = $fileLocation.'index.html';
      mkdir($fileLocation,0777,true);
      // $anchors = $doc->getElementsByTagName('a');
      // foreach ($anchors as $key => $a) {
      //   // out($s->getAttribute('src'));
      //   $href = $a->getAttribute('href');
      //   $parse_href = parse_url($href);
      //   if (isset($parse_href['host'])) {
      //     if ($parse_href['host'] == $this->siteHost) {
      //       out('href: '.$href);
      //       out($parse_href);
      //       $a->setAttribute('href',$parse_href['path']);
      //       //change url
      //     }
      //   }
      // }

      $saved = $doc->saveHTMLFile($filePath);
      if ($saved) {
        $r = [
          's3' => $s3Path.'index.html',
          'local' => $filePath,
          'ext' => 'html',
          'filename' => 'index.html'
        ];
        $this->exports[] = $r;
      }else {
        out($fileLocation . " did not save...");
      }
    }
  }
}

$export = new s3Export($urls['url']);
$export->sendAssets();
