<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->css('style.css') ?>
<?= $this->Html->css('animate.css') ?>

<script type="text/javascript">
    var checkSDesc = true;
    var checkLDesc = true;

    function countShortChars() {
        var count = document.getElementById("countshort");
        var message = document.getElementById("shortdesc").value;

        if (250 - message.length == 250)
            checkSDesc = false;
        else if (250 - message.length > 0) {
            count.innerHTML = (250 - message.length).toString().concat(' characters left');
            count.style.color = 'black';
            checkSDesc = true;
        }
        else {
            count.innerHTML = '0 characters left!';
            count.style.color = 'orangered';
            checkSDesc = false;
        }
    }

    function countLongChars() {
        var count = document.getElementById("countlong");
        var message = document.getElementById("description").value;

        if (1500 - message.length > 0) {
            count.innerHTML = (1500 - message.length).toString().concat(' characters left');
            count.style.color = 'black';
            checkLDesc = true;
        }
        else {
            count.innerHTML = '0 characters left!';
            count.style.color = 'orangered';
            checkLDesc = false;
        }
    }

    function checkButton() {
        document.getElementById('nextstep').disabled = !(checkSDesc && checkLDesc);
    }
</script>

<?= $this->Flash->render(); ?>
<div class="container">
    <div class="text-center teal-text darken-4"><h2><b>Update Car Information: Step 4</b></h2></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%">
            <div class="header bg-indigo darken-4">
                <h2>Admin: <?= $admin['name']; ?>
                    <div class="progress" style="width: 75%; margin-bottom: 0;">
                        <div class="progress-bar bg-teal progress-bar-striped" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"
                             style="width: 72%">Step 4 of 5</div>
                    </div>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li><?= $this->Html->link(__('Finish'), ['action' => 'delete', $car['id']], ['confirm' => __('{0} will be completely removed from the system. Are you sure to cancel?', $car['make'].' '.$car['model'].' '.$car['year']), 'class' => 'waves-effect waves-light btn btn-sm btn-danger disabled']); ?></li>
                </ul>
            </div>
            <?= $this->Form->create($car); ?>
            <div class="card-body">
                <div class="table" style="margin: 0;">
                    <table class="table-responsive" style="margin: 0;">
                        <tr><th scope="row">Short Desc</th>
                            <td><?= $this->Form->textarea('shortdesc', ['class' => 'form-control', 'type' => 'text', 'placeholder' => '* Required', 'label' => false, 'id' => 'shortdesc', 'oninput' => 'countShortChars();checkButton()']); ?>
                                <p class="small" id="countshort">250 characters left</p></td></tr>
                        <tr><th scope="row">Long Desc</th>
                            <td><?= $this->Form->textarea('description', ['class' => 'form-control', 'type' => 'text', 'label' => false, 'id' => 'description', 'oninput' => 'countLongChars();checkButton()']); ?>
                                <p class="small" id="countlong">1500 characters left</p></td></tr>
                        <tr><th scope="row">Photos</th>
                            <td><?= $this->Form->control('description', ['class' => 'form-control', 'type' => 'file', 'label' => false, 'id' => 'photos']); ?>
                                <p class="small">JPG only. Size below 2MB.</p></td></tr>
                    </table>
                </div>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Next Step'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'nextstep', 'type' => 'submit', 'style' => 'width: 200px;']); ?>
                <?= $this->Form->end(); ?>
                <?= $this->Html->link(__('Save for later'), ['action' => 'revive'], ['class' => 'btn btn-lg btn-warning lighten-2 waves-effect waves-light', 'style' => 'width: 200px;']); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
