let userScore = 0;
let compScore = 0;

let choices = document.querySelectorAll(".choice");
let msg = document.querySelector("#msg");
let userScorePara = document.querySelector("#user-score");
let comScorePara = document.querySelector("#com-score");
let drawScorePara = document.querySelector("#draw-score");


const genCompChoice = () =>{
    const options = ["rock","paper","scissors"];
    const ranIdx = Math.floor(Math.random() * 3);
    return options[ranIdx];
    //rock,paper,scissors

};

const drawGame = () =>{
     
        msg.innerText = "Game was Draw, Play again....";
        msg.style.backgroundColor = "yellow";
        msg.style.color = "black";
       
       
};

const showWinner = (userWin,userChoice,comChoice) =>{
        if(userWin){
            userScore++;
            userScorePara.innerText = userScore;
            console.log("You Win");
            msg.innerText = `You Win!! your ${userChoice} beats ${comChoice}` ;

            msg.style.backgroundColor = "green";
            msg.style.color = "white";
        }else{
            compScore++
            comScorePara.innerText= compScore
            console.log("You lose");
            msg.innerText = `You Lost. ${comChoice} beats your ${userChoice}`;
            msg.style.backgroundColor = "red";
            msg.style.color = "white";
        }
    
};

const playGame = (userChoice) =>{
    console.log("User choice =", userChoice);
    const comChoice = genCompChoice();
    console.log("Computer choice ", comChoice);
    let drawScore ;
    if( userChoice === comChoice){
        drawGame();
        drawScore++;
    }
    else{
        let userWin = true;
        if(userChoice === "rock"){
           userWin = comChoice === "paper" ? false : true;
        } else if(userChoice === "paper"){
            userWin = comChoice === "scissors" ? true : false;
        } else{
            userWin = comChoice === "rock" ? false : true;
        }
    showWinner(userWin,userChoice,comChoice);
    }
};



choices.forEach((choice) =>{
 
    choice.addEventListener("click", () => {
        const userChoice = choice.getAttribute("id");
        playGame(userChoice);
    });
});