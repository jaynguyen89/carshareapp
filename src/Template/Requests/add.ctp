<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?= $this->Html->css('style.css') ?>

<script type="text/javascript">
    var titleVal = false;
    var contentVal = false;
    var fileVal = false;

    function countTitle() {
        var count = document.getElementById("counttitle");
        var title = document.getElementById("title").value;

        if (50 - title.length > 0) {
            count.innerHTML = (50 - title.length).toString().concat(' characters left');
            count.style.color = 'black';
            titleVal = true;
        }
        else {
            count.innerHTML = '0 char left!';
            count.style.color = 'orangered';
            titleVal = false;
        }
    }

    function countContent() {
        var count = document.getElementById("countcontent");
        var content = document.getElementById("content").value;

        if (500 - content.length > 0) {
            count.innerHTML = (500 - content.length).toString().concat(' characters left');
            count.style.color = 'black';
            contentVal = true;
        }
        else {
            count.innerHTML = '0 char left!';
            count.style.color = 'orangered';
            contentVal = false;
        }
    }

    function checkForm() {
        var title = document.getElementById("title").value;
        var content = document.getElementById("content").value;
        var file = document.getElementById('file').value.toLowerCase();
        var fileEr = document.getElementById('fileEr');
        var submit = document.getElementById('submit');

        if (title.length === 0)
            titleVal = false;

        if (content.length === 0)
            contentVal = false;

        var exts = ['.pdf', '.jpg', '.jpeg', '.png'];
        if (file.length !== 0) {
            for (var i = 0; i < exts.length; i++) {
                if (file.indexOf(exts[i]) !== -1) {
                    fileVal = true;
                    fileEr.style.display = 'none';
                    break;
                }

                if (i === exts.length - 1 && file.indexOf(exts[i]) === -1) {
                    fileEr.innerHTML = 'File format is not allowed. Please choose another file.';
                    fileEr.style.display = '';
                    fileVal = false;
                }
            }
        }
        else {
            fileEr.style.display = 'none';
            fileVal = true;
        }

        submit.disabled = !(titleVal && contentVal && fileVal);
    }
</script>

<?= $this->Flash->render(); ?>
<div class="container">
    <div class="text-center teal-text darken-4"><h4><b>Send Request</b></h4></div>
    <div class="row">
        <div class="card horizontal" style="width: 100%;">
            <div class="header bg-indigo darken-4">
                <p style="font-size: larger;">Customer: <?= $customer['name']; ?>
                <small style="color: #aeea00 !important;">Ask our staff to help you out on your rentals</small></p>
                <ul class="header-dropdown m-r--5"><li><?= $this->Html->link(__('Cancel'), ['controller' => 'customers', 'action' => 'dashboard', $customer['user_id']], ['class' => 'waves-effect waves-light btn btn-sm btn-danger']) ;?></li></ul>
            </div>
            <?= $this->Form->create($request); ?>
            <div class="card-body">
                <table class="table" style="margin-bottom: 0;">
                    <tr><th scope="row">Rental</th>
                        <td><input value="<?= $rental['id']; ?>" id="rentalid" class="form-control" name="rentalid" label="false" type="text" disabled />
                            <p><b>Car:</b> <?= $car['make'].' '.$car['model'].' '.$car['year'].' ('.$car['color'].')'; ?></p>
                            <p><b>Date:</b> <?= (new DateTime($rental['fromdate']))->format('d/m/Y H:i'); ?> - <?php $label = ($rental['type'] == 'short') ? ' hour(s)' : ' day(s)'; echo $rental['duration'].$label; ?></p></td>
                    </tr>
                    <tr><th scope="row">Title</th>
                        <td><?= $this->Form->control('title', array('class' => 'form-control', 'label' => false, 'id' => 'title', 'type' => 'text', 'required' => true, 'oninput' => 'countTitle()', 'onchange' => 'checkForm()')); ?>
                            <p id="counttitle" class="small">50 characters left</p></td>
                    </tr>
                    <tr><th scope="row">Content</th>
                        <td><?= $this->Form->textarea('content', array('class' => 'form-control', 'label' => false, 'id' => 'content', 'type' => 'text', 'required' => true, 'oninput' => 'countContent()', 'onchange' => 'checkForm()')); ?>
                            <p id="countcontent" class="small">500 characters left</p></td>
                    </tr>
                    <tr><th scope="row">Attachment</th>
                        <td><?= $this->Form->file('file', array('class' => 'form-control', 'label' => false, 'id' => 'file', 'onchange' => 'checkForm()')); ?>
                            <small>Accepted formats: PDF, JPG, JPEG, PNG. Size < 2.0 MB</small><p id="fileEr" class="small" style="display: none; color: orangered;"></p></td>
                    </tr>
                </table>
            </div>
            <div class="card-action">
                <?= $this->Form->button(__('Send'), ['class' => 'btn btn-lg btn-info lighten-2 waves-effect waves-light', 'id' => 'submit', 'type' => 'submit', 'disabled' => true]); ?>
            </div>
            <?php $this->Form->end(); ?>
        </div>
    </div>
</div>

<?= $this->Html->script('admin.js') ?>
<?= $this->Html->script('colored.js') ?>
