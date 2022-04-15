class InsertBlock{

    constructor(containerBlockIdentity, blockToInsert, textToInsert, clickButtonIdentity){
        this.containerBlockIdentity = containerBlockIdentity;
        this.blockToInsert = blockToInsert;
        this.textToInsert = textToInsert;
        this.clickButtonIdentity = clickButtonIdentity;
    }

    insertBlock(){

        var button = document.querySelectorAll(this.clickButtonIdentity);
        
        for(var i = 0; i < button.length; i++){
            button[i].addEventListener('click', function(){
            console.log(this.textToInsert);
        
            var block = document.createElement(this.blockToInsert);
            block.innerHTML = this.textToInsert ;
            containerBlock = document.querySelector(this.containerBlockIdentity);
            containerBlock.appendChild(block);
            });
        }

    }

}