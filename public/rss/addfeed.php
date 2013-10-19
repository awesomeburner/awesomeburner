<script src="email/prototype.js" type="text/javascript"></script>
<script src="email/email.js" type="text/javascript"></script>
<link rel="stylesheet" href="../css/style.css" type="text/css">
<center>
<div id="results" style="width: 320px; text-align: center; padding: 7px; color: red;">
<script language="javascript">
	if (getcookie("email") != null){
		var message = getcookie("email").split(",");
		document.write("You have signed up for an e-mail alert succesfully with " +message)
	} else {
		document.write('Enter your e-mail address:<br>')
		document.write('<input type="text" maxlength="50" style="width:300px" name="email" id="email" onkeydown="checkemail()" onkeyup="checkemail()"> <img src="email/blank.gif" id="valid" title="">')
		document.write('<div id="sending" style="width: 300px; text-align: center; color: red;">')
		document.write('</div>')
	}
</script>
</div>
</center>
