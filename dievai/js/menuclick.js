var button = document.querySelectorAll("button.btn.bg-deep-purple.waves-effect");

for(var i = 0; i < button.length; i++){
    button[i].addEventListener('click', function(){
        // alert("button was clicked");
        // var HTMLText = 'some text';
        var HTMLText =
        `
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>
      `;
      console.log(HTMLText);

        blockToInsert = document.createElement( 'div' );
        blockToInsert.innerHTML = HTMLText ;
        containerBlock = document.querySelector('div.modal-insert-place');
        containerBlock.appendChild( blockToInsert );


        // var div = document.createElement('div');
        // div.className = 'insert-rows';
        // div.appendChild(div);
    });
}