function createAlert(alertType, msg){
    let alert = document.createElement("DIV");
    alert.classList.add("alert");
    alert.innerHTML = "<span class='closebtn'>&times;</span>" + msg;
    switch(alertType){
        case "SUCCESS":
            alert.classList.add("success");
            break;
        case "INFO":
            alert.classList.add("info");
            break;
        default:
            break;
    }
    alert.querySelector("span.closebtn").addEventListener("click", () => {
        alert.style.opacity = "0";
        setTimeout(function(){ alert.style.display = "none"; }, 600);
    })
    document.querySelector("div.alerts").appendChild(alert);
}