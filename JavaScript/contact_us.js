


var helpArray = ["Enter your full name", "Enter your e-mail adress in the format shown in the box.",
  "Enter your phone number, it should consist of 10 digit, and starts with 05",
   "Your message should not exceed 200 charecters in length.", ""];

var fullnameHelp, emailHelp, phoneHelp, messageHelp;

function init() {
    fullnameHelp = document.getElementById("fullnameHelp");
    regListener(fullnameHelp, document.getElementById("fullName"), 0);

    emailHelp = document.getElementById("emailHelp");
    regListener(emailHelp, document.getElementById("email"), 1);

    phoneHelp = document.getElementById("phoneHelp");
    regListener(phoneHelp, document.getElementById("phone"), 2);

    messageHelp = document.getElementById("messageHelp");
    regListener(messageHelp, document.getElementById("msg"), 3);

}

function regListener(element, object, msgIndx) {
  object.addEventListener("focus", function () { 
    if (element != null){
    element.innerHTML = helpArray[msgIndx]; 
    element.style.display = "block"; }}, false);
  object.addEventListener("blur", function () { element.innerHTML = helpArray[4]; element.style.display = "none"; }, false);

}


window.addEventListener("load", init, false);
