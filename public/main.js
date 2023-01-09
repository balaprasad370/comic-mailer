"use strict";

function mailChecker(email) {

    // Regular Expression (Not accepts second @ symbol
    // before the @gmail.com and accepts everything else)
    var regexp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    // Converting the email to lowercase
    return regexp.test(String(email).toLowerCase());
}

const email = document.getElementById("email");
const emailInputErr = document.getElementById("email-input-err");
const loadEmailErr = document.getElementById("load-err");
//	console.log(loadEmailErr);
email.addEventListener("focusout", function () {
    //	console.log(email.value.length);
    if (email.value.length == 0) {
        //	console.log(email.value.length);
        emailInputErr.innerHTML = "<p style='color:red;'>Email is required</p>";
        loadEmailErr.style.display = "none";
    }
    else if (!mailChecker(email.value)) {
        email.style.border = "1px solid #ff0000";
        emailInputErr.innerHTML = "<p style='color:red;'>Email is not valid</p>";
        loadEmailErr.style.display = "none";
    } else {
        email.style.border = "1px solid #fff";
        emailInputErr.innerHTML = "";
        loadEmailErr.style.display = "none";
    }
})

email.addEventListener("focusin", function () {

    loadEmailErr.style.display = "none";

});
// for debug
// console.log(isEmail("dca@q.com"));
