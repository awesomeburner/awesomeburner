pic1= new Image(16,16); 
pic1.src="email/loading.gif"; 
pic2= new Image(16,16); 
pic2.src="email/x.png"; 
pic3= new Image(16,16); 
pic3.src="email/valid.png"; 

function writecookie(content)
{
  var expiredate = new Date
  expiredate.setMonth(expiredate.getFullYear()+9)
  document.cookie = "email="+ content+";expires="+expiredate.toGMTString()
}

function checkemail()
{
  var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i
  var returnval=emailfilter.test(document.getElementById('email').value)

  if (returnval==false) {
    document.getElementById('valid').src = 'email/x.png'
    document.getElementById('sending').innerHTML = ''
  } else {
    document.getElementById('valid').src = 'email/valid.png'
    document.getElementById('sending').innerHTML = '<input type="button"  value="submit" class="button" onclick="submitemail(document.getElementById(\'email\').value)">'
  }
}

function submitemail(email)
{
  document.getElementById('sending').innerHTML = '<img src="email/loading.gif">'
   new Ajax.Request( 'email/write.php?var='+encodeURIComponent(email), {method:'get', onSuccess:Rcvd, onFailure:Failed});
}

//// Place failure message here
function Failed()
{
document.getElementById('results').innerHTML = 'Sorry, there was a failure in your query. Refresh and try again!'
}

//// Place confirm message here
function Rcvd()
{
writecookie(document.getElementById('email').value)
document.getElementById('results').innerHTML = 'Your e-mail address has been succesfully submitted!'
}

//// Don't edit this.
function getcookie(c_name)
{
  if (document.cookie.length > 0) {
    var c_start = document.cookie.indexOf(c_name + "=");

    if (c_start != -1) {
      c_start = c_start + c_name.length + 1;
      var c_end = document.cookie.indexOf(";",c_start);

      if(c_end == -1)
        c_end = document.cookie.length;

      return unescape(document.cookie.substring(c_start, c_end));
    }
  }
}
