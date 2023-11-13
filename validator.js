let x, y, r;

function checkResponse(response) {

    if (response.status !== 200) {
        alert("Произошла ошибка. Пожалуйста повторите попытку позже. Ошибка: " + response.status);
        return ;
    }
    return response.text();
}

window.onload = function (){
    let buttons = document.querySelectorAll("input[name=X-button]");
    buttons.forEach(click);

    function click(element){
        element.onclick = function (){
            x = this.value;
            buttons.forEach(function (element){
                element.style.boxShadow = "";
                element.style.transform = "";
            });
            this.style.boxShadow = "0 0 40px 5px deeppink";
            this.style.transform = "scale(1.05)";
        }
    }

    let data = new FormData();
    data.append('save', 'true');
    fetch('answer.php', {
        method: 'POST',
        body: data
    }).then(response => response.text())
        .then(function (responseTXT) {
            document.getElementById("outputContainer").innerHTML = responseTXT;
        })
};

document.getElementById("checkButton").onclick = function (){
    if (validateX() && validateY() && validateR()){
        let data = new FormData();
        data.append('x', x)
        data.append('y', y)
        data.append('r', r)
        data.append('timezone', Intl.DateTimeFormat().resolvedOptions().timeZone);
        fetch('answer.php',{
            method: "POST",
            body: data
        }).then(response => checkResponse(response)).then(function (responseTXT){
            document.getElementById("outputContainer").innerHTML = responseTXT;
        }).catch(err => alert("Произошла ошибка. Пожалуйста повторите попытку позже. Ошибка: " + err));
    }
};

document.getElementById("resetButton").onclick = function (){
    let data = new FormData();
    data.append('reset', 'true');
    fetch('answer.php',{
        method: "POST",
        body: data
    }).then(() => location.reload());
}

function validateY(){
    y = document.querySelector("input[name=Y-input]").value;
    if (y === undefined){
        alert("y не введён.");
        return false;
    }else if(!isNumeric(x)){
        alert("y не число.");
        return false;
    }else if ((y <= -5) || (y >= 3)){
        alert("y не входит в область допустимых значений.");
        return false;
    }else return true;
}

function validateX(){
    if (isNumeric(x)) return true;
    else {
        alert("x не выбран.");
        return false;
    }
}

function validateR(){
    r = document.querySelector("input[name=R-input]").value;
    if (r === undefined){
        alert("r не введён.");
        return false;
    }else if(!isNumeric(r)){
        alert("r не число.");
        return false;
    }else if ((r <= 2) || (r >= 5)){
        alert("r не входит в область допустимых значений.");
        return false;
    }else return true;
}

function isNumeric(n){
    return !isNaN(parseFloat(n)) && isFinite(n);
}
