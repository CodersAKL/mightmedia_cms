
<div class="alert alert-warning alert---with-cta">
    <div class="alert--info">
        <i class="material-icons">warning</i>
        <strong>Nepamirškite!</strong>
        Prieš atnaujindami sistemą rekomenduojame pasidaryti duomenų bazės ir failų kopijas.
    </div>
    <div class="alert--cta">
        <form action="" method="post" name="upgrade-form">
            <input type="hidden" name="upgrade" value="1">
            <button type="submit" class="btn btn-default waves-effect" onclick="return confirm('Are you sure?');">
                Pradėti atnaujinimą
            </button>
        </form>
    </div>
</div>

<div class="card hide card--updates">
    <div class="header">
        <h2>
            Atnaujinimai
        </h2>
    </div>
    <div class="body">
        <ul class="list-group"></ul>
    </div>
</div>
<script>
    var form = document.querySelector('[name="upgrade-form"]');
    var cardUpdates = document.querySelector('.card--updates');
    var listUpdates = cardUpdates.querySelector('.list-group');
    var setStep = function(step){
        var newLi = document.createElement('li');
        newLi.innerHTML = step;
        newLi.classList.add('list-group-item');

        listUpdates.appendChild(newLi);
    },
    stepActive = function(step){
        var lastStep = listUpdates.querySelector('.list-group-item:nth-child(' + step + ')');

        lastStep.classList.add('list-group-item-success');
    },
    callStep = function(postData, step) {
        data = {
            action: 'upgrade' + step + 'Step',
            action_functions: 'upgrade',
            data: postData
        };
        $.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
			if(response) {
                var data = JSON.parse(response);
				console.log(data);
                if(data.type === 'error') {
                    showNotification('error', data.message);
                } else {
                    stepActive(step - 1);
                    setStep(data.step);
                    
                    if(data.end && data.end == true) {
                        stepActive(step);
                        showNotification('success', data.step);
                    } else {
                        callStep(data.data, step + 1);
                    }
                }
			}
		});
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        data = {
            action: 'upgradeInit',
            action_functions: 'upgrade',
            data: $('[name="upgrade-form"]').serializeArray()
        };

		$.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
			if(response) {
                var data = JSON.parse(response);
				console.log(data);
                if(data.type === 'error') {
                    showNotification('error', data.message);
                } else {
                    cardUpdates.classList.remove('hide');
                    setStep(data.step);
                    if(data.nextStep){
                        callStep(data.data, data.nextStep);
                    }
                }
                
			}
		});
    });
</script>