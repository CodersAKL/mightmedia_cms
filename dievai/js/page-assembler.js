function saugoti(){
   
    var divList = document.getElementById("page-builder-zone");
    console.log(divList);
    divListas = toObject(divList);
    console.log(divListas);
    console.log(setParent(divListas));

    for (let index = 0; index < divList.length; index++) {
        //console.log(divList[index]);
    }
   
}
function toObject(arr) {
    var rv = {};
    for (var i = 0; i < arr.length; ++i)
      rv[i] = arr[i];
    return rv;
}

function search(parent){
    var parent = document.querySelector('#page-builder-zone');
    getParent(parent);   
}

function getParent(parent){
    idIndex = +document.getElementById("indexID").value;
    parentIdIndex = +document.getElementById("parentIdInde").value;
    var siblings = parent.children;
    
   
    var orderIdIndex = 0
    
    for (let index = 0; index < Object.keys(siblings).length; index++) {
        idIndex += 1;
        var childs = siblings[index].children;
        
        orderIdIndex += 1;
           // siblings[index].setAttribute('data', "id:'" +id  + "', parentId:'"+ parentId +"', orderId:'"+ orderId +"'");
            siblings[index].dataset.id = idIndex;
            siblings[index].dataset.parentId = parentIdIndex;
            siblings[index].dataset.orderId = orderIdIndex;
            
           
            console.log(idIndex);
            console.log(siblings[index]);
        if (Object.keys(childs).length == 0 ){
            //Galutiniai elementai img h1..h5 span ir t.t.
            siblings[index].dataset.id = idIndex;
            siblings[index].dataset.parentId = parentIdIndex;
            siblings[index].dataset.orderId = orderIdIndex;
           // console.log(getChildren(siblings[index]));
        } else {
            //console.log(siblings[index]);
            lastParent = document.querySelectorAll("[data-parent-id='" + parentIdIndex + "']");
            //Elementai kurie dar turi savyje Child'u
            
            document.getElementById("indexID").value = idIndex;
            getParent(siblings[index]);
        }
        parentIdIndex += 1;
        document.getElementById("parentIdInde").value = idIndex;
        
    }
}

function getChildren(parent){
    var children = parent.children[0];
    return children;
}

function setParent(object, parent) {
    object.parent = parent;
    object.children && object.children.forEach(function (o) {
        setParent(o, object);
    });
}



function doSwap(a, b) {
    console.log('a: ' + a + ' | b: ' + b);
    swapElements(document.getElementById(a), document.getElementById(b));
}

function swapElements(obj1, obj2) {
    // save the location of obj2
    var parent2 = null;
    if (obj2.parentNode == null){
        parent2 = obj2;
    } else {
        parent2 = obj2.parentNode;
    }
    var next2 = obj2.nextSibling;
    // special case for obj1 is the next sibling of obj2
    if (next2 === obj1) {
        // just put obj1 before obj2
        parent2.insertBefore(obj1, obj2);
    } else {
        // insert obj2 right before obj1
        console.log(obj1.parentNode);
        obj1.parentNode.insertBefore(obj2, obj1);

        // now insert obj1 where obj2 was
        if (next2) {
            // if there was an element after obj2, then insert obj1 right before that
            parent2.insertBefore(obj1, next2);
        } else {
            // otherwise, just append as last child
            parent2.appendChild(obj1);
        }
    }
}

class App {
   
    static init() {
        
        App.box = document.getElementsByClassName('box')[0]
        App.box.addEventListener("dragstart", App.dragstart)
        App.box.addEventListener("dragend", App.dragend)
        const containers = document.getElementsByClassName('holder')

        for(const container of containers) {
            container.addEventListener("dragover", App.dragover)
            container.addEventListener("dragenter", App.dragenter)
            container.addEventListener("dragleave", App.dragleave)
            container.addEventListener("drop", App.drop)
        }
    }

    static dragstart() {
        //this.className += " held"
        this.classList.add('held')

        //setTimeout(()=>this.classList.add('invisible'), 0)
    }

    static dragend() {
        this.classList.remove('held')
    }

    static dragover(e) {
        e.preventDefault()
        //console.log(e);
    }

    static dragenter(e) {
        e.preventDefault()
        this.classList.add('hovered')
       // this.className += " hovered"
    }

    static dragleave() {
        this.classList.remove('holder')
        //this.className = "holder"
    }

    static drop() {
       // this.className = "holder"
       this.classList.remove('hovered')
       this.classList.remove('invisible')
        var drag = App.box.id;
        var place = null;
        if (this.id){
            place = document.getElementById(this.id).children[0].id;
        }
        if (!place){        
            this.append(App.box)
        } else {
            doSwap(place,drag);
        }
        
        
    }
}

function addClassBox(elem) {
    elem.setAttribute('draggable', 'true');
    // get all 'div' elements
    var div = document.getElementsByTagName('div');
    // loop through all 'a' elements
    
    // add 'box' classs to the element that was clicked
    if (elem.classList.contains('box')){
        elem.classList.remove('box');

    } else {
        for (i = 0; i < div.length; i++) {
            // Remove the class 'box' if it exists
            div[i].classList.remove('box')
            div[i].classList.remove('hovered')
        }
        elem.classList.add('box');
    }
    
    App.init();
}

// document.addEventListener("click", function(event){
//     var targetElement = event.target || event.srcElement;
//     console.log(targetElement.parentNode);
//     //addClassBox(targetElement.parentNode);
// });

function CssFileItraukimas(){
    var link = document.createElement( "link" );
    src="../dievai/css/Test.css"; //pakeisti css faila i reikiama
    link.href = src;
    link.type = "text/css";
    link.rel = "stylesheet";
    link.media = "screen,print";

    document.getElementsByTagName( "head" )[0].appendChild( link );
}


