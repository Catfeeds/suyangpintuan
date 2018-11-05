<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $heading; ?></title>
<style type="text/css">
html{overflow:auto}
.error_body{background-color:#fff;margin:30px;font-family:Lucida Grande,Verdana,Sans-serif;font-size:12px;color：#000}
#content{border:#ccc 1px solid;background-color:#fff;padding:0; margin-bottom:5px}
#content h1{font-weight:bold;font-size:14px;color:#900;margin:0 0 5px 0;background-color:#eee; line-height:14px; padding:10px; border-bottom:1px solid #ddd}
#content p{padding-left:15px; border-bottom:1px solid #eee; line-height:34px; margin:0}
</style>
</head>
<body class="error_body">
<div id="content">
    <h1><?php echo $heading; ?></h1>
    <?php echo $message; ?>
</div>
</body>
</html>