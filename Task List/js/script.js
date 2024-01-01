//define UI element 
let form = document.querySelector('#task_form');
let taskList = document.querySelector('ul');
let clearbtn = document.querySelector('#clear_task_btn');
let filter = document.querySelector('#task_filter');
let taskInput = document.querySelector('#new_task');


// define event listener

form.addEventListener('submit', addTask);
taskList.addEventListener('click', removetask);
clearbtn.addEventListener('click', clearTask)
// define function
//ADD TASK

function addTask(e) {
    if (taskInput.value === ''){
       alert("Add a task!");
    }
    else{ 
        // create li element
        let li = document.createElement("li");
        let b = document.createTextNode(taskInput.value + " ");
        li.appendChild(b);
        let link = document.createElement('a');
        link.setAttribute('href', '#');
        link.innerHTML = 'x';
        li.appendChild(link);
        taskList.appendChild(li);
        taskInput.value = '';
    }
    e.preventDefault();
}

// Remove Task
function removetask(e){
    if(e.target.hasAttribute('href')){
        if(confirm('Are you sure?')){
            let ele = e.target.parentElement;
            ele.remove();
        }
    //console.log(e.target);
}
}

// Clear Task

function clearTask(e){
    // General Way
    //taskList.innerHTML = "";

    //Faster Way
    while(taskList.firstChild){
        taskList.removeChild(taskList.firstChild);
    }
}

