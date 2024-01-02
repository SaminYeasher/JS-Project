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
filter.addEventListener('keyup', filtertask);
document.addEventListener('DOMContentLoaded', getTasks);

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
        
        storeTaskInLocalStorage(taskInput.value);

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
            //console.log(e.target);
            removeFromLS(ele);
        }
        
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
    localStorage.clear();
}



//Filter Task
function filtertask(e){
    let text = e.target.value.toLowerCase();

    document.querySelectorAll('li').forEach( task => {
        let item = task.firstChild.textContent;
        if(item.toLowerCase().indexOf(text) != -1){
            task.style.display = 'block';
        }
        else{
            task.style.display = 'none';
        }
    });
}


// Store In Local Storage
function storeTaskInLocalStorage(task){
    let tasks;
    if(localStorage.getItem('tasks') === null ) {
      tasks = [];  
    } else {
        tasks = JSON.parse(localStorage.getItem('tasks'))
    }
    tasks.push(task);

    localStorage.setItem('tasks', JSON.stringify(tasks));
}

// Document Loaded
function getTasks(){
    let tasks;
    if(localStorage.getItem('tasks') === null ) {
      tasks = [];  
    } else {
        tasks = JSON.parse(localStorage.getItem('tasks'))
    }

    tasks.forEach( task =>{
        let li = document.createElement("li");
        let b = document.createTextNode(task + " ");
        li.appendChild(b);
        let link = document.createElement('a');
        link.setAttribute('href', '#');
        link.innerHTML = 'x';
        li.appendChild(link);
        taskList.appendChild(li);

    });
}


function removeFromLS(taskItem){
    let tasks;
    if(localStorage.getItem('tasks') === null ) {
      tasks = [];  
    } else {
        tasks = JSON.parse(localStorage.getItem('tasks'))
    }

    let li = taskItem;
    li.removeChild(li.lastChild);

    tasks.forEach((task,index) =>{
            if(li.textContent.trim() ===  task){
                tasks.splice(index,1);
            }
    });

    localStorage.setItem('tasks', JSON.stringify(tasks));
}


