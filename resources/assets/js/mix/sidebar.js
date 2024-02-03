var coll = document.getElementsByClassName("collapsible-controllers");
var i;
var iconc = document.getElementById("caret-controllers");

for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        iconc.classList.toggle('open');
        this.classList.toggle("active");

        var content = this.nextElementSibling;

        if (content.style.maxHeight){
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });

    for (i = 0; i < coll.length; i++) {
        if (coll[i].nextElementSibling.innerHTML.indexOf("active") !== -1) {
            coll[i].click();
        }
    }
}

var coll = document.getElementsByClassName("collapsible-train");
var i;
var icont = document.getElementById("caret-train");

for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        icont.classList.toggle('open');
        this.classList.toggle("active");

        var content = this.nextElementSibling;

        if (content.style.maxHeight){
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });

    for (i = 0; i < coll.length; i++) {
        if (coll[i].nextElementSibling.innerHTML.indexOf("active") !== -1) {
            coll[i].click();
        }
    }
}

var coll = document.getElementsByClassName("collapsible-admin");
var i;
var icona = document.getElementById("caret-admin");

for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        icona.classList.toggle('open');
        this.classList.toggle("active");

        var content = this.nextElementSibling;

        if (content.style.maxHeight){
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });

    for (i = 0; i < coll.length; i++) {
        if (coll[i].nextElementSibling.innerHTML.indexOf("active") !== -1) {
            coll[i].click();
        }
    }
}
