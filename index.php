<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>

<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link href="style.css" rel="stylesheet" type="text/css" />

<title>URL Shortner</title>
  
</head>

<body>
  <script>
    function copyShorturl() {
      var short_url = document.getElementById("short-url");
      short_url.select();
      document.execCommand("copy");     
      var tooltip = document.getElementById("copy-tooltip");
      tooltip.innerHTML ="Copied ";
    }

    function outFocus() {
      var tooltip = document.getElementById("copy-tooltip");
      tooltip.innerHTML = "Copy URL";
    }
  </script>
<div class="container">

  <?php 
  require_once('./config.php');

  $enteredUrl = "";
  $short_url = "";

  // check for url in the request and create shortcode for the given url
  if (isset($_GET['url']) && $_GET['url']!="")
  { 
  
    $enteredUrl=urldecode($_GET['url']);
    if (filter_var($enteredUrl, FILTER_VALIDATE_URL)) 
    {
      // Create connection
      $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        } 
      $short_url=getShortUrl($enteredUrl);
      $conn->close();

    } 
    else 
    {
      die("$enteredUrl is not a valid URL");
    }

  }?>

  <center>
  <h3>Your long URL</h3>

  <form>
    <div class="form-group">
      <input type="url" class="form-control" id="url" name="url" required value="<?php echo $enteredUrl?>">
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Make it Short</button>
    </div>
  </form>

  <?php if($short_url !=""){ ?>
  
    <div class="form-group">
      <label class="control-label col-sm-3" for="short-link">Short URL</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" value="<?php echo BASE_URL; echo"/"; echo $short_url; ?>" id="short-url">
      </div>
      <div class="col-sm-3">
        <button onclick="copyShorturl()" onmouseout="outFocus()" class="btn btn-outline-primary btn-default"><span id="copy-tooltip" >Copy URL</button>
      </div>
    </div>
  <?php
    } 
  ?>
  </center>
  </div> 


<?php

/*
Function    : getShortCode()
Description : To check whether the short_code for given url is present in the db ,if not create new short_code.
parameter   : enteredUrl
return      : Short_code
*/
  function getShortUrl($enteredUrl){
    global $conn;
    $query = "SELECT * FROM tbl_shorturl WHERE url = '".$enteredUrl."' "; 
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['short_code'];
    } else {
      $short_code = generateShortCode();

      $sql = "INSERT INTO tbl_shorturl (url, short_code)
      VALUES ('".$enteredUrl."', '".$short_code."')";
      if ($conn->query($sql) === TRUE) {
        return $short_code;
      } else { 
        die("Unknown Error Occured");
      }
    }
  }

/*
Function    : generateShortCode
Description : To generate unique Short code.
parameter   : None
return      : Unique id
*/

  function generateShortCode(){    
    global $conn; 
    $charset = str_shuffle(CHARSET);
    $uniqCode = substr($charset, 0, URL_LENGTH);// creates a  unique short id.

    $query = "SELECT * FROM tbl_shorturl WHERE short_code = '".$uniqCode."' ";
    $result = $conn->query($query); 
    if ($result->num_rows > 0) {
      generateShortCode();
    } else {
        return $uniqCode;
    }
  }

//----check request for shortcode and redirect to actual url
  if(isset($_GET['redirect']) && $_GET['redirect']!="")
  { 
    $short_url = urldecode($_GET['redirect']);
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $enteredUrl= getUrl($short_url);
    $conn->close();
    header("location:".$enteredUrl);
    exit;
  }

/*
Function    : getUrl
Description : To fetch the actual url.
parameter   : short_url
return      : Actual url.
*/

  function getUrl($short_url){
    global $conn;
    $query = "SELECT * FROM tbl_shorturl WHERE short_code = '".addslashes($short_url)."' "; 
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['url'];
      }
      else 
      { 
        die("Invalid Link!");
      }
  }

  ?>
  </div>
</body>
  
